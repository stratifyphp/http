<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;

/**
 * Delegate the processing of a request to another middleware.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class DelegateToMiddleware implements DelegateInterface
{
    /**
     * @var MiddlewareInvoker
     */
    private $invoker;

    private $middleware;

    /**
     * @var DelegateInterface
     */
    private $next;

    public function __construct(MiddlewareInvoker $invoker, $middleware, DelegateInterface $next)
    {
        $this->invoker = $invoker;
        $this->middleware = $middleware;
        $this->next = $next;
    }

    public function process(RequestInterface $request)
    {
        return $this->invoker->invoke($this->middleware, $request, $this->next);
    }
}
