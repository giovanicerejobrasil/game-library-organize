<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch;

use App\Models\Game;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GameSearchService
{
    protected string $host;

    protected string $index;

    protected float $timeout;

    public function __construct()
    {
        $this->host = rtrim(Config::get('elasticsearch.host', 'http://127.0.0.1:9200'), '/');
        $this->index = Config::get('elasticsearch.index', 'games');
        $this->timeout = (float) Config::get('elasticsearch.timeout', 3.0);
    }

    /**
     * Verifica se o Elasticsearch está online e acessível
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout($this->timeout)->get($this->host);

            return $response->successful();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Cria ou atualiza o índice de jogos no Elasticsearch com analisadores e mappings
     */
    public function createIndex(): bool
    {
        $url = "{$this->host}/{$this->index}";

        $mapping = [
            'settings' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
                'analysis' => [
                    'filter' => [
                        'game_edge_ngram' => [
                            'type' => 'edge_ngram',
                            'min_gram' => 2,
                            'max_gram' => 20,
                        ],
                    ],
                    'analyzer' => [
                        'game_autocomplete' => [
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => ['lowercase', 'game_edge_ngram'],
                        ],
                        'game_search' => [
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => ['lowercase'],
                        ],
                    ],
                ],
            ],
            'mappings' => [
                'properties' => [
                    'id' => ['type' => 'long'],
                    'title' => [
                        'type' => 'text',
                        'analyzer' => 'game_search',
                        'fields' => [
                            'keyword' => ['type' => 'keyword'],
                            'autocomplete' => [
                                'type' => 'text',
                                'analyzer' => 'game_autocomplete',
                                'search_analyzer' => 'game_search',
                            ],
                        ],
                    ],
                    'slug' => ['type' => 'keyword'],
                    'synopsis' => ['type' => 'text'],
                    'developer' => [
                        'type' => 'text',
                        'fields' => ['keyword' => ['type' => 'keyword']],
                    ],
                    'publisher' => [
                        'type' => 'text',
                        'fields' => ['keyword' => ['type' => 'keyword']],
                    ],
                    'franchise_name' => [
                        'type' => 'text',
                        'fields' => ['keyword' => ['type' => 'keyword']],
                    ],
                    'is_franchise' => ['type' => 'boolean'],
                    'release_year' => ['type' => 'integer'],
                    'genre' => ['type' => 'keyword'],
                    'platforms' => ['type' => 'keyword'],
                    'libraries' => ['type' => 'keyword'],
                    'cover_image' => ['type' => 'keyword', 'index' => false],
                    'background_image' => ['type' => 'keyword', 'index' => false],
                    'trailer_url' => ['type' => 'keyword', 'index' => false],
                    'age_rating' => ['type' => 'keyword'],
                    'created_at' => ['type' => 'date'],
                ],
            ],
        ];

        try {
            // Deleta o índice existente se houver
            Http::timeout($this->timeout)->delete($url);

            // Cria o índice com as novas configurações
            $response = Http::timeout($this->timeout)->put($url, $mapping);

            return $response->successful();
        } catch (Throwable $e) {
            Log::warning('Erro ao criar índice no Elasticsearch: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Indexa ou atualiza um jogo no Elasticsearch
     */
    public function indexGame(Game $game): bool
    {
        $game->loadMissing(['platforms', 'libraries']);

        $document = [
            'id' => $game->id,
            'title' => $game->title,
            'slug' => $game->slug,
            'synopsis' => $game->synopsis,
            'developer' => $game->developer,
            'publisher' => $game->publisher,
            'franchise_name' => $game->franchise_name,
            'is_franchise' => (bool) $game->is_franchise,
            'release_year' => $game->release_year,
            'genre' => $game->genre ?? [],
            'platforms' => $game->platforms->pluck('slug')->all(),
            'libraries' => $game->libraries->pluck('slug')->all(),
            'cover_image' => $game->cover_image,
            'background_image' => $game->background_image,
            'trailer_url' => $game->trailer_url,
            'age_rating' => $game->age_rating,
            'created_at' => $game->created_at?->toISOString(),
        ];

        $url = "{$this->host}/{$this->index}/_doc/{$game->id}";

        try {
            $response = Http::timeout($this->timeout)->put($url, $document);

            return $response->successful();
        } catch (Throwable $e) {
            Log::warning("Erro ao indexar jogo {$game->id} no Elasticsearch: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Remove um jogo do índice
     */
    public function deleteGame(int $gameId): bool
    {
        $url = "{$this->host}/{$this->index}/_doc/{$gameId}";

        try {
            $response = Http::timeout($this->timeout)->delete($url);

            return $response->successful();
        } catch (Throwable $e) {
            Log::warning("Erro ao remover jogo {$gameId} do Elasticsearch: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Executa busca estruturada com suporte a filtros combinados
     *
     * @param  array{
     *     query?: string|null,
     *     platforms?: array<string>|null,
     *     libraries?: array<string>|null,
     *     genre?: string|array<string>|null,
     *     release_year?: int|null,
     *     page?: int,
     *     per_page?: int
     * }  $filters
     * @return array{total: int, hits: array<int, array<string, mixed>>, ids: array<int, int>}
     */
    public function search(array $filters): array
    {
        $must = [];
        $filter = [];

        $queryText = trim((string) ($filters['query'] ?? ''));

        if ($queryText !== '') {
            $must[] = [
                'multi_match' => [
                    'query' => mb_strtolower($queryText),
                    'fields' => [
                        'title^4',
                        'title.autocomplete^3',
                        'franchise_name^3',
                        'developer^2',
                        'publisher^2',
                        'synopsis',
                    ],
                    'type' => 'bool_prefix',
                    'fuzziness' => 'AUTO',
                ],
            ];
        }

        if (! empty($filters['platforms'])) {
            $filter[] = [
                'terms' => ['platforms' => (array) $filters['platforms']],
            ];
        }

        if (! empty($filters['libraries'])) {
            $filter[] = [
                'terms' => ['libraries' => (array) $filters['libraries']],
            ];
        }

        if (! empty($filters['genre'])) {
            $genres = (array) $filters['genre'];
            $filter[] = [
                'terms' => ['genre' => $genres],
            ];
        }

        if (! empty($filters['release_year'])) {
            $filter[] = [
                'term' => ['release_year' => (int) $filters['release_year']],
            ];
        }

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = max(1, min(100, (int) ($filters['per_page'] ?? 24)));
        $from = ($page - 1) * $perPage;

        $body = [
            'from' => $from,
            'size' => $perPage,
            'query' => [
                'bool' => [
                    'must' => empty($must) ? [['match_all' => new \stdClass]] : $must,
                    'filter' => $filter,
                ],
            ],
            'sort' => [
                '_score' => ['order' => 'desc'],
                'title.keyword' => ['order' => 'asc'],
            ],
        ];

        try {
            $response = Http::timeout($this->timeout)->post("{$this->host}/{$this->index}/_search", $body);

            if ($response->successful()) {
                $data = $response->json();
                $total = (int) ($data['hits']['total']['value'] ?? 0);
                $rawHits = $data['hits']['hits'] ?? [];

                $hits = [];
                $ids = [];

                foreach ($rawHits as $hit) {
                    $source = $hit['_source'] ?? [];
                    $hits[] = $source;
                    if (isset($source['id'])) {
                        $ids[] = (int) $source['id'];
                    }
                }

                return [
                    'total' => $total,
                    'hits' => $hits,
                    'ids' => $ids,
                ];
            }
        } catch (Throwable $e) {
            Log::warning('Falha na busca do Elasticsearch: '.$e->getMessage());
        }

        // Retorno padrão em caso de indisponibilidade
        return [
            'total' => 0,
            'hits' => [],
            'ids' => [],
        ];
    }

    /**
     * Reconstrói o índice e indexa todos os jogos do banco
     */
    public function reindexAll(): int
    {
        $this->createIndex();

        $count = 0;
        Game::with(['platforms', 'libraries'])->chunk(100, function ($games) use (&$count) {
            foreach ($games as $game) {
                if ($this->indexGame($game)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
