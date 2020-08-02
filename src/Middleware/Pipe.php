<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;

/**
 * Pipes middlewares to call them in order.
 *
 * This is also a middleware so that it can be used like any other middleware.
 */
class Pipe implements MiddlewareInterface, RequestHandlerInterface
{
    /** @var MiddlewareInterface[] */
    private array $middlewares;

    private MiddlewareInvoker $invoker;

    public function __construct(array $middlewares, MiddlewareInvoker $invoker = null)
    {
        $this->middlewares = $middlewares;
        $this->invoker = $invoker ?: new SimpleInvoker;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process($request, new LastHandler);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        foreach (array_reverse($this->middlewares) as $middleware) {
            $handler = new MiddlewareInvokerDelegate($this->invoker, $middleware, $handler);
        }

        // Invoke the root middleware
        return $handler->handle($request);
    }
}
