<?php

namespace Stratify\Http\Exception;

/**
 * An exception that represents a HTTP error response.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class HttpException extends \Exception
{
    private $statusCode;
    private $headers;

    public function __construct(
        int $statusCode,
        string $message = null,
        \Exception $previous = null,
        array $headers = []
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, 0, $previous);
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }
}
