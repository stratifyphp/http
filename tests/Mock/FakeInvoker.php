<?php
declare(strict_types=1);

namespace Stratify\Http\Test\Mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Middleware\Invoker\MiddlewareInvoker;

class FakeInvoker implements MiddlewareInvoker
{
    private array $entries;

    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function invoke(
        $middleware,
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        // Calls with the parameters reversed
        return call_user_func($this->entries[$middleware], $handler, $request);
    }
}
