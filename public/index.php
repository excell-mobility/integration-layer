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
 * API v1
 *
 *
 * The API v1 exposes the following calls:
 *
 * GET  /health         returns the service's current status (UP or DOWN), optionally status of internal or external resources are added.
 *
 * POST /auth/tokens    creates a new signed JSON Web Token and returns HTTP 201 Created
 *
 */
$app->get('/api/v1/health',         Api\v1\Health\GetHealth::class,         'api.v1.health');
$app->post('/api/v1/auth/tokens',   Api\v1\Auth\Token\PostToken::class,     'api.v1.auth.tokens');



/**
 * All the web pages are handled here
 *
 */
$app->get('/',                      Page\HomePage::class,                   'home');


/**
 * Add admin backend stuff here.
 *
 */

/**
 * Run that!
 *
 */
$app->run();
