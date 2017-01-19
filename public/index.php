<?php

namespace App;

use App\Api;
use App\Page;
use LosMiddleware\LosLog\ErrorLogger;
use Zend\Expressive\Application;

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

// register error & shutdown handler
ErrorLogger::registerHandlers('error.log', 'data/log');

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Zend\Expressive\Application $app */
$app = $container->get(Application::class);

/**
 * API v1.0
 */
$app->get( '/api/v1/ping',          Api\v1\GetPing::class,                  'api.v1.ping');

$app->post('/api/v1/auth/tokens',   Api\v1\Auth\Token\PostToken::class,     'api.v1.auth.tokens');

/**
 * Pages
 */
$app->get('/',                      Page\HomePage::class,                   'home');

$app->run();
