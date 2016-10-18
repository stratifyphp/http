<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\LastHandler;
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
            function () {
                return new SimpleResponse('Hello');
            },
        ]);

        $response = $pipe->process(new ServerRequest, new LastHandler);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function accepts_custom_invoker()
    {
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
            'third' => function () {
                return new SimpleResponse('Hello');
            },
        ]);

        $pipe = new Pipe([
            'first',
            'second',
            'third',
        ], $invoker);

        $response = $pipe->process(new ServerRequest, new LastHandler);

        $this->assertEquals('Hello world!', $response->getBody()->__toString());
    }
}
