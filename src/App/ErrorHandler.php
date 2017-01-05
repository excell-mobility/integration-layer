<?php
namespace App;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Log\Logger;

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
class ErrorHandler
{
    /**
     * Logger instance.
     * @var Logger
     */
    protected $logger;

    /**
     * Just log the exception here.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke($error, $request,  $response,  $next) : Response
    {
        try {
            $result = $next($request, $response);
            if (!($result instanceof Response)) {
                throw new Exception('error: no response returned!');
            }
        } catch (Throwable $exception) {
            $this->logger->crit($exception);
            $result = new HtmlResponse('error', 500);
        }

        return $result;
    }

    /**
     * Sets the logger instance.
     *
     * @param Logger $logger
     * @return ErrorHandler
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
        return $this;
    }
}