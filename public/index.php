<?php

namespace App;

use Zend\Expressive\Application;

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));

// log all fatal errors via shutdown handler
require_once './src/App/ShutdownHandler.php';

require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Zend\Expressive\Application $app */
$app = $container->get(Application::class);

/**
 * API v1.0
 */
$app->get('/api/v1.0/ping', Action\PingAction::class, 'api.ping');


/**
 * Homepage
 */
$app->get('/', Action\HomePageAction::class, 'home');

$app->run();
