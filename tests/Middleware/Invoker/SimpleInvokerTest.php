<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware\Invoker;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;
use Stratify\Http\Middleware\LastHandler;
use Stratify\Http\Test\TestResponse;

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
            $this->assertInstanceOf(RequestHandlerInterface::class, $args[1]);

            return $expectedResponse;
        };

        $invoker = new SimpleInvoker;
        $actualResponse = $invoker->invoke($callable, $request, new LastHandler);

        $this->assertEquals(1, $calls);
        $this->assertSame($expectedResponse, $actualResponse);
    }

    /**
     * @test
     */
    public function invokes_callable_middlewares()
    {
        $middleware = function () {
            return new TestResponse('Hello world!');
        };

        $response = (new SimpleInvoker)->invoke($middleware, new ServerRequest, new LastHandler);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function invokes_interop_middlewares()
    {
        $middleware = new class() implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
            {
                return new TestResponse('Hello world!');
            }
        };

        $response = (new SimpleInvoker)->invoke($middleware, new ServerRequest, new LastHandler);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }
}
