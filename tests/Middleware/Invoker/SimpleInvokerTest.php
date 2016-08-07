<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware\Invoker;

use Stratify\Http\Middleware\Invoker\SimpleInvoker;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\ServerRequest;

class SimpleInvokerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function passes_middleware_parameters()
    {
        $request = new ServerRequest;
        $response = new Response;

        $calls = 0;
        $expectedResponse = new EmptyResponse;
        $callable = function () use (&$calls, $request, $response, $expectedResponse) {
            $calls++;

            $args = func_get_args();

            $this->assertCount(3, $args);
            $this->assertSame($request, $args[0]);
            $this->assertSame($response, $args[1]);
            $this->assertTrue(is_callable($args[2]));

            return $expectedResponse;
        };

        $invoker = new SimpleInvoker;
        $actualResponse = $invoker->invoke($callable, $request, $response, function () {});

        $this->assertEquals(1, $calls);
        $this->assertSame($expectedResponse, $actualResponse);
    }
}
