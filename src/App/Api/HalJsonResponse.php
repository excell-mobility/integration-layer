<?php
namespace App\Api;

use Nocarrier\Hal;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

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
class HalJsonResponse extends Response
{
    public function __construct(Hal $halObject, $status = 200, array $headers = [])
    {
        $body = new Stream('php://temp', 'wb+');
        $body->write($halObject->asJson(true));
        $body->rewind();

        parent::__construct($body, $status, array('Content-Type' => 'application/hal+json'));
    }
}