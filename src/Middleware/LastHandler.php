<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Stratify\Http\Exception\HttpNotFound;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LastHandler implements DelegateInterface
{
    public function process(RequestInterface $request)
    {
        // No middleware handled the HTTP request
        throw new HttpNotFound;
    }
}
