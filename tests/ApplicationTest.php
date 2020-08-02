<?php
declare(strict_types = 1);

namespace Stratify\Http\Test;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Application;
use Stratify\Http\Test\TestResponse;
use Stratify\Http\Test\Mock\FakeEmitter;
use Stratify\Http\Test\Mock\FakeInvoker;

class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function runs_and_emits_the_response()
    {
        $middleware = function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
            return new TestResponse('Hello world!');
        };

        $responseEmitter = new FakeEmitter;
        $app = new Application($middleware, null, $responseEmitter);
        $app->run();

        $this->assertEquals('Hello world!', $responseEmitter->output);
    }

    /**
     * @test
     */
    public function accepts_psr15_middlewares()
    {
        $middleware = new class() implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return new TestResponse('Hello world!');
            }
        };

        $responseEmitter = new FakeEmitter;
        $app = new Application($middleware, null, $responseEmitter);
        $app->run();

        $this->assertEquals('Hello world!', $responseEmitter->output);
    }

    /**
     * @test
     */
    public function handles_a_request_and_returns_a_response()
    {
        $request = $this->getMockForAbstractClass(ServerRequestInterface::class);

        $middleware = function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
            return new TestResponse('Hello world!');
        };

        $app = new Application($middleware, null, new FakeEmitter);
        $response = $app->handle($request);
        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function invokes_middleware_using_the_invoker()
    {
        $invoker = new FakeInvoker([
            // parameters are reversed in FakeInvoker
            'foo' => function (RequestHandlerInterface $handler, ServerRequestInterface $request) {
                return new TestResponse('Hello world!');
            },
        ]);

        $responseEmitter = new FakeEmitter;
        $app = new Application('foo', $invoker, $responseEmitter);
        $app->run();

        $this->assertEquals('Hello world!', $responseEmitter->output);
    }

    /**
     * @test
     */
    public function is_a_middleware()
    {
        $this->assertInstanceOf(MiddlewareInterface::class, new Application(''));
    }
}
