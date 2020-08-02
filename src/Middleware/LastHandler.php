<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stratify\Http\Exception\HttpNotFound;

/**
 * We are at the end of the stack: not found!
 */
class LastHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new HttpNotFound;
    }
}
