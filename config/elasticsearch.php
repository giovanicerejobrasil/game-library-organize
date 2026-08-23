<?php

declare(strict_types=1);

return [
    'host' => env('ELASTICSEARCH_HOST', 'http://127.0.0.1:9200'),
    'index' => env('ELASTICSEARCH_INDEX', 'games'),
    'retries' => (int) env('ELASTICSEARCH_RETRIES', 2),
    'timeout' => (float) env('ELASTICSEARCH_TIMEOUT', 3.0),
];
