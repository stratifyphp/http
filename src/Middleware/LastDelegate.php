<?php
declare(strict_types=1);

namespace Stratify\Http\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stratify\Http\Exception\HttpNotFound;

/**
 * Delegate that do not chain to another delegate.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LastDelegate implements DelegateInterface
{
    public function process(ServerRequestInterface $request)
    {
        throw new HttpNotFound;
    }
}
