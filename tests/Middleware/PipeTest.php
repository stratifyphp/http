<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Pipe;
use Stratify\Http\Response\SimpleResponse;
use Stratify\Http\Test\Mock\FakeInvoker;
use Zend\Diactoros\ServerRequest;

class PipeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calls_middlewares_in_correct_order()
    {
        $leaf = function () {
            return new SimpleResponse('Hello');
        };

        $pipe = new Pipe([
            function (ServerRequestInterface $request, callable $next) {
                $response = $next($request);
                $response->getBody()->write('!');
                return $response;
            },
            function (ServerRequestInterface $request, callable $next) {
                $response = $next($request);
                $response->getBody()->write(' world');
                return $response;
            },
        ]);

        $response = $pipe->__invoke(new ServerRequest, $leaf);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function accepts_custom_invoker()
    {
        $leaf = function () {
            return new SimpleResponse('Hello');
        };

        // The fake invoker passes the middleware parameters in the reverse order (for testing)
        $invoker = new FakeInvoker([
            'first' => function (callable $next, ServerRequestInterface $request) {
                $response = $next($request);
                $response->getBody()->write('!');
                return $response;
            },
            'second' => function (callable $next, ServerRequestInterface $request) {
                $response = $next($request);
                $response->getBody()->write(' world');
                return $response;
            },
        ]);

        $pipe = new Pipe([
            'first',
            'second',
        ], $invoker);

        $response = $pipe->__invoke(new ServerRequest, $leaf);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }
}
