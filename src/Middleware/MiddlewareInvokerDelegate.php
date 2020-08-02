<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MiddlewareInvokerDelegate implements RequestHandlerInterface
{
    private MiddlewareInvoker $invoker;

    /** @var MiddlewareInterface|callable|string|array */
    private $middleware;

    private RequestHandlerInterface $handler;

    /**
     * @param MiddlewareInterface|callable|string|array $middleware The middleware doesn't have to be callable.
     *                                                              That allows to resolve it from a container.
     */
    public function __construct(
        MiddlewareInvoker $invoker,
        $middleware,
        RequestHandlerInterface $handler
    ) {
        $this->invoker = $invoker;
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->invoker->invoke($this->middleware, $request, $this->handler);
    }
}
