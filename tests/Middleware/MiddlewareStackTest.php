<?php

namespace Stratify\Http\Test\Middleware;

use Invoker\Invoker;
use Invoker\ParameterResolver\AssociativeArrayResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\MiddlewareStack;
use Stratify\Http\Test\Mock\FakeContainer;
use Stratify\Http\Test\Mock\FakeInvoker;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class MiddlewareStackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calls_middleware_stack_in_correct_order()
    {
        $leaf = function ($req, ResponseInterface $res) {
            $res->getBody()->write('!');
            return $res;
        };

        $stack = new MiddlewareStack([
            function ($req, ResponseInterface $res, callable $next) {
                $res->getBody()->write('Hello');
                return $next($req, $res);
            },
            function ($req, ResponseInterface $res, callable $next) {
                $res->getBody()->write(' world');
                return $next($req, $res);
            },
        ]);

        $response = $stack->__invoke(new ServerRequest, new Response, $leaf);

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

        $stack = new MiddlewareStack([
            'first',
            'second',
        ], $invoker);

        $response = $stack->__invoke(new ServerRequest, new Response, $leaf);

        $this->assertEquals('Hello world!', $response->getBody());
    }
}
