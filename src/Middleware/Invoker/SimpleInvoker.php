<?php

namespace Stratify\Http\Middleware\Invoker;

use Invoker\InvokerInterface;

/**
 * Simple invoker that expect the callable to be actually callable and
 * that just pass $request, $response and $next to middlewares.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SimpleInvoker implements InvokerInterface
{
    public function call($callable, array $parameters = [])
    {
        if (! is_callable($callable)) {
            throw new \Exception('The controller is not callable');
        }

        if (!isset($parameters['request']) || !isset($parameters['response']) || !isset($parameters['next'])) {
            throw new \Exception('Expected "request", "response" and "next" in parameters');
        }

        $request = $parameters['request'];
        $response = $parameters['response'];
        $next = $parameters['next'];

        return call_user_func($callable, $request, $response, $next);
    }
}
