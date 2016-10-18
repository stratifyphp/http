<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
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
class Pipe implements ServerMiddlewareInterface
{
    /**
     * @var ServerMiddlewareInterface[]|mixed[]
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

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $next = $delegate;

        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = new DelegateToMiddleware($this->invoker, $middleware, $next);
        }

        // Invoke the root middleware
        return $next->process($request);
    }
}
