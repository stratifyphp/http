<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware\Invoker;

use Interop\Http\Middleware\DelegateInterface;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;
use Stratify\Http\Middleware\LastHandler;
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

        $calls = 0;
        $expectedResponse = new EmptyResponse;
        $callable = function () use (&$calls, $request, $expectedResponse) {
            $calls++;

            $args = func_get_args();

            $this->assertCount(2, $args);
            $this->assertSame($request, $args[0]);
            $this->assertInstanceOf(DelegateInterface::class, $args[1]);

            return $expectedResponse;
        };

        $invoker = new SimpleInvoker;
        $actualResponse = $invoker->invoke($callable, $request, new LastHandler);

        $this->assertEquals(1, $calls);
        $this->assertSame($expectedResponse, $actualResponse);
    }
}
