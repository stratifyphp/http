<?php
declare(strict_types = 1);

namespace Stratify\Http;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;
use Stratify\Http\Middleware\LastHandler;

/**
 * An HTTP application emits a response for the current request.
 *
 * Note that the application is also a middleware.
 */
class Application implements MiddlewareInterface, RequestHandlerInterface
{
    /** @var mixed */
    private $middleware;

    private MiddlewareInvoker $invoker;

    private EmitterInterface $responseEmitter;

    public function __construct($middleware, MiddlewareInvoker $invoker = null, EmitterInterface $responseEmitter = null)
    {
        $this->middleware = $middleware;
        $this->invoker = $invoker ?: new SimpleInvoker;
        $this->responseEmitter = $responseEmitter ?: new SapiEmitter;
    }

    /**
     * Handle the global incoming request and sends the response.
     *
     * @see handle() to handle an HTTP request and not write the response to the output.
     */
    public function run(ServerRequestInterface $request = null): void
    {
        $request = $request ?: ServerRequestFactory::fromGlobals();

        $response = $this->handle($request);

        $this->responseEmitter->emit($response);
    }

    /**
     * Handle the given HTTP request and returns an HTTP response.
     *
     * Unlike run() this method doesn't write anything to the output. Use it in tests.
     *
     * @see run() for a more high-level method.
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return $this->process($request, new LastHandler);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return $this->invoker->invoke($this->middleware, $request, $handler);
    }
}
