<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware\Invoker;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Simple invoker that expect the callable to be actually callable and
 * that just pass $request, $response and $next to middlewares.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SimpleInvoker implements MiddlewareInvoker
{
    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) : ResponseInterface
    {
        if ($middleware instanceof ServerMiddlewareInterface) {
            $response = $middleware->process($request, $delegate);
            if (!$response instanceof ResponseInterface) {
                throw new \Exception('The middleware did not return a response');
            }
            return $response;
        }

        if (! is_callable($middleware)) {
            throw new \Exception('The middleware is not callable');
        }

        return call_user_func($middleware, $request, $delegate);
    }
}
