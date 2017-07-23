<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Pipe;
use Stratify\Http\Response\SimpleResponse;
use Stratify\Http\Test\Mock\FakeInvoker;
use Stratify\Http\Test\Mock\FakeLastDelegate;
use Zend\Diactoros\ServerRequest;

class PipeTest extends TestCase
{
    /**
     * @test
     */
    public function calls_middlewares_in_correct_order()
    {
        $leaf = new FakeLastDelegate(function () {
            return new SimpleResponse('Hello');
        });

        $pipe = new Pipe([
            function (ServerRequestInterface $request, DelegateInterface $delegate) {
                $response = $delegate->process($request);
                $response->getBody()->write('!');
                return $response;
            },
            function (ServerRequestInterface $request, DelegateInterface $delegate) {
                $response = $delegate->process($request);
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
        $leaf = new FakeLastDelegate(function () {
            return new SimpleResponse('Hello');
        });

        // The fake invoker passes the middleware parameters in the reverse order (for testing)
        $invoker = new FakeInvoker([
            'first' => function (DelegateInterface $delegate, ServerRequestInterface $request) {
                $response = $delegate->process($request);
                $response->getBody()->write('!');
                return $response;
            },
            'second' => function (DelegateInterface $delegate, ServerRequestInterface $request) {
                $response = $delegate->process($request);
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
