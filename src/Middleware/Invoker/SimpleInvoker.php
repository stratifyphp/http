<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware\Invoker;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Simple invoker that expect the callable to be actually callable and
 * that just pass $request and $handler to middlewares.
 */
class SimpleInvoker implements MiddlewareInvoker
{
    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $handler);
        }

        if (! is_callable($middleware)) {
            throw new \Exception('The middleware is not callable');
        }

        return $middleware($request, $handler);
    }
}
