<?php

namespace Stratify\Http\Middleware;

use Invoker\InvokerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;

/**
 * Stacks middlewares to call them in order.
 *
 * This is also a middleware so that it can be used like any other middleware.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MiddlewareStack implements Middleware
{
    /**
     * @var Middleware[]
     */
    private $middlewares;

    /**
     * @var InvokerInterface
     */
    private $invoker;

    public function __construct(array $middlewares, InvokerInterface $invoker = null)
    {
        $this->middlewares = $middlewares;
        $this->invoker = $invoker ?: new SimpleInvoker;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface
    {
        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = function (ServerRequestInterface $request, ResponseInterface $response) use ($middleware, $next) {
                return $this->invoker->call($middleware, [
                    'request' => $request,
                    'response' => $response,
                    'next' => $next,
                ]);
            };
        }

        // Invoke the root middleware
        return $next($request, $response);
    }
}
