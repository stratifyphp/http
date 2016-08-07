<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Pipe;
use Stratify\Http\Test\Mock\FakeInvoker;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class PipeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calls_middlewares_in_correct_order()
    {
        $leaf = function ($req, ResponseInterface $res) {
            $res->getBody()->write('!');
            return $res;
        };

        $pipe = new Pipe([
            function ($req, ResponseInterface $res, callable $next) {
                $res->getBody()->write('Hello');
                return $next($req, $res);
            },
            function ($req, ResponseInterface $res, callable $next) {
                $res->getBody()->write(' world');
                return $next($req, $res);
            },
        ]);

        $response = $pipe->__invoke(new ServerRequest, new Response, $leaf);

        $this->assertEquals('Hello world!', $response->getBody());
    }

    /**
     * @test
     */
    public function accepts_custom_invoker()
    {
        $leaf = function ($req, ResponseInterface $res) {
            $res->getBody()->write('!');
            return $res;
        };

        // The fake invoker passes the middleware parameters in the reverse order (for testing)
        $invoker = new FakeInvoker([
            'first'  => function (callable $next, ResponseInterface $response, ServerRequestInterface $request) {
                $response->getBody()->write('Hello');
                return $next($request, $response);
            },
            'second' => function (callable $next, ResponseInterface $response, ServerRequestInterface $request) {
                $response->getBody()->write(' world');
                return $next($request, $response);
            },
        ]);

        $pipe = new Pipe([
            'first',
            'second',
        ], $invoker);

        $response = $pipe->__invoke(new ServerRequest, new Response, $leaf);

        $this->assertEquals('Hello world!', $response->getBody());
    }
}
