<?php

namespace Stratify\Http\Test\Middleware;

use Psr\Http\Message\ResponseInterface;
use Stratify\Http\Middleware\MiddlewareStack;
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
}
