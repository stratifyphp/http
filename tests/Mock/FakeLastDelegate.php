<?php
declare(strict_types=1);

namespace Stratify\Http\Test\Mock;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;

class FakeLastDelegate implements DelegateInterface
{
    /**
     * @var callable
     */
    private $action;

    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    public function process(ServerRequestInterface $request)
    {
        return ($this->action)($request);
    }
}
