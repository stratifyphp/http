<?php
declare(strict_types = 1);

namespace Stratify\Http\Exception;

/**
 * An exception that represents a HTTP error response.
 */
class HttpException extends \Exception
{
    private int $statusCode;
    private array $headers;

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
