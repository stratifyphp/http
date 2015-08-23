<?php

namespace Stratify\Http\Exception;

/**
 * HTTP exception: 405 method not allowed.
 */
class HttpMethodNotAllowed extends HttpException
{
    public function __construct(array $allowedMethods)
    {
        parent::__construct(405, 'HTTP method not allowed, allowed methods: ' . implode(', ', $allowedMethods));
    }
}
