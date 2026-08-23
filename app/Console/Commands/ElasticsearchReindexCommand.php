<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Elasticsearch\GameSearchService;
use Illuminate\Console\Command;

class ElasticsearchReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria o índice de jogos no Elasticsearch e reindexa todos os jogos do banco de dados';

    /**
     * Execute the console command.
     */
    public function handle(GameSearchService $searchService): int
    {
        $this->info('Verificando conexão com o Elasticsearch...');

        if (! $searchService->isAvailable()) {
            $this->warn('O serviço do Elasticsearch não está acessível no host configurado. Verifique se o container/serviço está em execução.');

            return self::FAILURE;
        }

        $this->info('Criando e configurando o índice...');
        $created = $searchService->createIndex();

        if (! $created) {
            $this->error('Falha ao criar o índice no Elasticsearch.');

            return self::FAILURE;
        }

        $this->info('Indexando jogos...');
        $indexed = $searchService->reindexAll();

        $this->info("Concluído com sucesso! Total de jogos indexados: {$indexed}.");

        return self::SUCCESS;
    }
}
