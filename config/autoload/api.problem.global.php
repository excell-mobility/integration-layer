<?php

return [
    'los_api_problem' => [
        'display_trace' => false,
    ],
    'dependencies' => [
        'invokables' => [
            LosMiddleware\ApiProblem\ApiProblem::class => LosMiddleware\ApiProblem\ApiProblem::class,
        ]
    ],
];
