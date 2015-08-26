<?php

namespace Stratify\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface
    {
        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = function (ServerRequestInterface $request, ResponseInterface $response) use ($middleware, $next) {
                return $middleware($request, $response, $next);
            };
        }

        // Invoke the root middleware
        return $next($request, $response);
    }
}
