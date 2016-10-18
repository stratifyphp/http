<?php
declare(strict_types = 1);

namespace Stratify\Http;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;
use Stratify\Http\Middleware\LastHandler;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

/**
 * An HTTP application emits a response for the current request.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Application implements ServerMiddlewareInterface
{
    /**
     * @var mixed
     */
    private $middleware;

    /**
     * @var MiddlewareInvoker
     */
    private $invoker;

    /**
     * @var EmitterInterface
     */
    private $responseEmitter;

    public function __construct($middleware, MiddlewareInvoker $invoker = null, EmitterInterface $responseEmitter = null)
    {
        $this->middleware = $middleware;
        $this->invoker = $invoker ?: new SimpleInvoker;
        $this->responseEmitter = $responseEmitter ?: new SapiEmitter();
    }

    /**
     * Handle the global incoming request and sends the response.
     *
     * @see handle() to handle an HTTP request and not write the response to the output.
     */
    public function run(ServerRequestInterface $request = null)
    {
        $request = $request ?: ServerRequestFactory::fromGlobals();

        $response = $this->process($request);

        $this->responseEmitter->emit($response);
    }

    /**
     * Process the given HTTP request and returns an HTTP response.
     *
     * Unlike run() this method doesn't write anything to the output. Use it in tests.
     *
     * @see run() for a more high-level method.
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate = null) : ResponseInterface
    {
        $delegate = $delegate ?: new LastHandler;

        return $this->invoker->invoke($this->middleware, $request, $delegate);
    }
}
