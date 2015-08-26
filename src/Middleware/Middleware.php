<?php

namespace Stratify\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Middlewares can be any PHP callable. If it's a class, it can optionally implement this interface.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Middleware
{
    /**
     * Handle the HTTP request to return an HTTP response.
     *
     * The `$next` callable can optionally be called (e.g. if the middleware cannot
     * handle the current request).
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface;
}
