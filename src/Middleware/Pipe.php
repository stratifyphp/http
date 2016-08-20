<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;
use Stratify\Http\Middleware\Invoker\SimpleInvoker;

/**
 * Pipes middlewares to call them in order.
 *
 * This is also a middleware so that it can be used like any other middleware.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Pipe implements Middleware
{
    /**
     * @var Middleware[]
     */
    private $middlewares;

    /**
     * @var MiddlewareInvoker
     */
    private $invoker;

    public function __construct(array $middlewares, MiddlewareInvoker $invoker = null)
    {
        $this->middlewares = $middlewares;
        $this->invoker = $invoker ?: new SimpleInvoker;
    }

    public function __invoke(ServerRequestInterface $request, callable $next) : ResponseInterface
    {
        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = function (ServerRequestInterface $request) use ($middleware, $next) {
                return $this->invoker->invoke($middleware, $request, $next);
            };
        }

        // Invoke the root middleware
        return $next($request);
    }
}
