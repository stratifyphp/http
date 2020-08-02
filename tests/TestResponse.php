<?php
declare(strict_types = 1);

namespace Stratify\Http\Test;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\InjectContentTypeTrait;
use Laminas\Diactoros\Stream;

/**
 * Simple plain text response to use when testing middlewares.
 *
 * Alternative to Diactoros TextResponse: this implementation will not rewind the stream,
 * allowing to easily write new content to the body.
 *
 * That is mostly useful to test middlewares.
 */
class TestResponse extends Response
{
    use InjectContentTypeTrait;

    /**
     * Create a plain text response.
     *
     * Produces a text response with a Content-Type of text/plain and a default
     * status of 200.
     */
    public function __construct(string $text, int $status = 200, array $headers = [])
    {
        $body = new Stream('php://temp', 'wb+');
        $body->write($text);

        parent::__construct(
            $body,
            $status,
            $this->injectContentType('text/plain; charset=utf-8', $headers)
        );
    }
}
