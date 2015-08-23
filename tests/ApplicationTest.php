<?php

namespace Stratify\Http\Test;

use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Application;
use Zend\Diactoros\Response\EmitterInterface;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function runs_and_emits_the_response()
    {
        $middleware = function (ServerRequestInterface $req, ResponseInterface $res, callable $next) {
            $res->getBody()->write('Hello world!');
        };

        $responseEmitter = $this->prophesize('Zend\Diactoros\Response\EmitterInterface');
        $responseEmitter->emit(Argument::that(function (ResponseInterface $response) {
            $this->assertEquals('Hello world!', $response->getBody()->__toString());
        }));
        $responseEmitter = $responseEmitter->reveal();

        $app = new Application($middleware, $responseEmitter);
        $app->run();
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
        };

        /** @var EmitterInterface $responseEmitter */
        $responseEmitter = $this->getMockForAbstractClass('Zend\Diactoros\Response\EmitterInterface');

        $app = new Application($middleware, $responseEmitter);
        $response = $app->handle($request);
        $this->assertEquals('Hello world', $response->getBody());
    }
}
