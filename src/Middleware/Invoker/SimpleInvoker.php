<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware\Invoker;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Simple invoker that expect the callable to be actually callable and
 * that just pass $request and $delegate to middlewares.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SimpleInvoker implements MiddlewareInvoker
{
    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) : ResponseInterface {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $delegate);
        }

        if (! is_callable($middleware)) {
            throw new \Exception('The middleware is not callable');
        }

        return $middleware($request, $delegate);
    }
}
