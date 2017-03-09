<?php
namespace App\Api\v1;

use App\Api\Validation;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Short description for class
 *
 * Long description for class
 *
 * @package   Broadcasting Service
 * @author    André Rademacher <andre.rademacher@entiretec.com>
 * @copyright Copyright (c) 2017 ENTIRETEC (http://www.entiretec.com)
 * @license   ENTIRETEC proprietery license
 */
class ValidateSslClientCertificate
{
    /**
     * Checks if a SSL client certificate is given and the web server's validation was successful.
     * If not, an exception is thrown and a HTTP 401 response generated.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if (strpos($request->getUri()->getPath(), '/api/v1') === 0) {
            $validation = new Validation($request);
            $validation->validateClientCertificate();
        }

        // call the next middleware in case there is one, and use its return as new response
        if ($next) {
            $response = $next($request, $response);
        }

        // return the response
        return $response;
    }
}