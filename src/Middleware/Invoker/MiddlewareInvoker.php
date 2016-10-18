<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware\Invoker;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
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
     * 
     * @param ServerMiddlewareInterface|callable|mixed $middleware
     */
    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) : ResponseInterface;
}
