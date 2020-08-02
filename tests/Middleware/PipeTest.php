<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Middleware\Pipe;
use Stratify\Http\Test\TestResponse;
use Stratify\Http\Test\Mock\FakeInvoker;
use Stratify\Http\Test\Mock\FakeLastHandler;

class PipeTest extends TestCase
{
    public function test calls middlewares in correct order()
    {
        $leaf = new FakeLastHandler(function () {
            return new TestResponse('Hello');
        });

        $pipe = new Pipe([
            function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                $response = $handler->handle($request);
                $response->getBody()->write('!');
                return $response;
            },
            function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
                $response = $handler->handle($request);
                $response->getBody()->write(' world');
                return $response;
            },
        ]);

        $response = $pipe->process(new ServerRequest, $leaf);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function accepts_custom_invoker()
    {
        $leaf = new FakeLastHandler(function () {
            return new TestResponse('Hello');
        });

        // The fake invoker passes the middleware parameters in the reverse order (for testing)
        $invoker = new FakeInvoker([
            'first' => function (RequestHandlerInterface $handler, ServerRequestInterface $request) {
                $response = $handler->handle($request);
                $response->getBody()->write('!');
                return $response;
            },
            'second' => function (RequestHandlerInterface $handler, ServerRequestInterface $request) {
                $response = $handler->handle($request);
                $response->getBody()->write(' world');
                return $response;
            },
        ]);

        $pipe = new Pipe([
            'first',
            'second',
        ], $invoker);

        $response = $pipe->process(new ServerRequest, $leaf);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }
}
