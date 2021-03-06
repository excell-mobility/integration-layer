<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            App\Page\HomePage::class => App\Factory\HomePageFactory::class,

            App\Api\v1\Auth\Token\PostToken::class => App\Factory\PostTokenFactory::class,
        ],
    ],
];
