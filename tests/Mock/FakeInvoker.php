<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;

class FakeInvoker implements MiddlewareInvoker
{
    /**
     * @var array
     */
    private $entries;

    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface
    {
        // Calls with the parameters reversed
        return call_user_func($this->entries[$middleware], $next, $response, $request);
    }
}
