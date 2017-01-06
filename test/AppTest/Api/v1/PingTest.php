<?php

namespace AppTest\Api\v1;

use App\Api\v1\GetPing;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class PingTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $ping = new GetPing();
        $response = $ping(new ServerRequest(['/']), new Response(), function () {
        });
        $json = json_decode((string) $response->getBody());

        $this->assertTrue($response instanceof Response);
        $this->assertTrue($response instanceof Response\JsonResponse);
        $this->assertTrue(isset($json->ack));
    }
}
