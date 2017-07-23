<?php
declare(strict_types=1);

namespace Stratify\Http\Exception;

/**
 * HTTP exception: 405 method not allowed.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class HttpMethodNotAllowed extends HttpException
{
    public function __construct(array $allowedMethods)
    {
        parent::__construct(405, 'HTTP method not allowed, allowed methods: ' . implode(', ', $allowedMethods));
    }
}
