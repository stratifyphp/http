<?php
declare(strict_types=1);

namespace Stratify\Http\Test\Mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FakeLastHandler implements RequestHandlerInterface
{
    /** @var callable */
    private $action;

    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->action)($request);
    }
}
