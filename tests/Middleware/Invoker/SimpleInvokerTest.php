<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware\Invoker;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;
use Stratify\Http\Middleware\LastDelegate;
use Stratify\Http\Response\SimpleResponse;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\ServerRequest;

class SimpleInvokerTest extends TestCase
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
        $actualResponse = $invoker->invoke($callable, $request, new LastDelegate);

        $this->assertEquals(1, $calls);
        $this->assertSame($expectedResponse, $actualResponse);
    }

    /**
     * @test
     */
    public function invokes_callable_middlewares()
    {
        $middleware = function () {
            return new SimpleResponse('Hello world!');
        };

        $response = (new SimpleInvoker)->invoke($middleware, new ServerRequest, new LastDelegate);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function invokes_interop_middlewares()
    {
        $middleware = new class() implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, DelegateInterface $delegate)
            {
                return new SimpleResponse('Hello world!');
            }
        };

        $response = (new SimpleInvoker)->invoke($middleware, new ServerRequest, new LastDelegate);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }
}
