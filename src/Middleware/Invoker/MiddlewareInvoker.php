<?php

namespace Stratify\Http\Middleware\Invoker;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Invokes a middleware.
 *
 * This abstraction allows to:
 *
 * - resolve middlewares from a DI container (or any other source)
 * - invoke the callables with different parameters (e.g. dependency injection in parameters)
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface MiddlewareInvoker
{
    /**
     * Invokes the given middleware using the provided parameters.
     *
     * The $middleware doesn't have to be callable.
     * That allows to resolve it from a container.
     */
    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface;
}
