<?php

declare(strict_types=1);

use App\Services\Elasticsearch\GameSearchService;
use Illuminate\Support\Facades\Http;

test('game search service handles query searches and returns structured responses', function () {
    Http::fake([
        'http://127.0.0.1:9200/games/_search' => Http::response([
            'hits' => [
                'total' => ['value' => 1],
                'hits' => [
                    [
                        '_source' => [
                            'id' => 42,
                            'title' => 'Elden Ring',
                            'slug' => 'elden-ring',
                            'developer' => 'FromSoftware',
                            'publisher' => 'Bandai Namco',
                            'release_year' => 2022,
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    $service = new GameSearchService;
    $result = $service->search(['query' => 'Elden']);

    expect($result)->toBeArray()
        ->and($result['total'])->toBe(1)
        ->and($result['ids'])->toBe([42])
        ->and($result['hits'][0]['title'])->toBe('Elden Ring');
});

test('game search service returns empty structure when elasticsearch is offline', function () {
    Http::fake([
        'http://127.0.0.1:9200/games/_search' => Http::response(null, 500),
    ]);

    $service = new GameSearchService;
    $result = $service->search(['query' => 'Zelda']);

    expect($result)->toBeArray()
        ->and($result['total'])->toBe(0)
        ->and($result['hits'])->toBe([])
        ->and($result['ids'])->toBe([]);
});
