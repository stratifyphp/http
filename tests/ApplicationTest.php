<?php

namespace Stratify\Http\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Application;
use Stratify\Http\Test\Mock\FakeEmitter;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function runs_and_emits_the_response()
    {
        $middleware = function (ServerRequestInterface $req, ResponseInterface $res, callable $next) {
            $res->getBody()->write('Hello world!');
            return $res;
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
        /** @var ServerRequestInterface $request */
        $request = $this->getMockForAbstractClass('Psr\Http\Message\ServerRequestInterface');

        $middleware = function (ServerRequestInterface $req, ResponseInterface $res, callable $next) {
            $res->getBody()->write('Hello world!');
            return $res;
        };

        $app = new Application($middleware, null, new FakeEmitter);
        $response = $app->handle($request);
        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }
}
