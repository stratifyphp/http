<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware\Invoker;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Invokes a middleware.
 *
 * This abstraction allows to:
 *
 * - resolve middlewares from a DI container (or any other source)
 * - invoke the callables with different parameters (e.g. dependency injection in parameters)
 */
interface MiddlewareInvoker
{
    /**
     * Invokes the given middleware using the provided parameters.
     *
     * @param MiddlewareInterface|callable|string|array $middleware The middleware doesn't have to be callable.
     *                                                              That allows to resolve it from a container.
     */
    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface;
}
