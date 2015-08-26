<?php

namespace Stratify\Http\Exception;

/**
 * HTTP exception: 404 not found.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class HttpNotFound extends HttpException
{
    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct(404, $message);
    }
}
