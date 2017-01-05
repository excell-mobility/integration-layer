<?php
return [
    'dependencies' => [
        'factories' => [
            LosMiddleware\LosLog\LosLog::class => LosMiddleware\LosLog\LosLogFactory::class,
            LosMiddleware\LosLog\HttpLog::class => LosMiddleware\LosLog\HttpLogFactory::class,
        ],
    ],
    'loslog' => [
        'log_dir' => 'data/log',
        'error_logger_file' => 'error.log',
        'exception_logger_file' => 'exception.log',
        'static_logger_file' => 'static.log',
        'http_logger_file' => 'http.log',
        'log_request' => false,
        'log_response' => false,
        'full' => false,
    ],

    'middleware_pipeline' => [
        'error' => [
            'middleware' => [
                LosMiddleware\LosLog\LosLog::class,
            ],
            'error'    => true,
            'priority' => -10000,
        ],
    ],
];

