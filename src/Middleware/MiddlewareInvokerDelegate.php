<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MiddlewareInvokerDelegate implements DelegateInterface
{
    /**
     * @var MiddlewareInvoker
     */
    private $invoker;

    /**
     * @var MiddlewareInterface|callable|string|array
     */
    private $middleware;

    /**
     * @var DelegateInterface
     */
    private $nextDelegate;

    /**
     * @param MiddlewareInterface|callable|string|array $middleware The middleware doesn't have to be callable.
     *                                                              That allows to resolve it from a container.
     */
    public function __construct(
        MiddlewareInvoker $invoker,
        $middleware,
        DelegateInterface $nextDelegate
    ) {
        $this->invoker = $invoker;
        $this->middleware = $middleware;
        $this->nextDelegate = $nextDelegate;
    }

    public function process(ServerRequestInterface $request)
    {
        return $this->invoker->invoke($this->middleware, $request, $this->nextDelegate);
    }
}
