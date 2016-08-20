<?php
declare(strict_types = 1);

namespace Stratify\Http\Response;

use Zend\Diactoros\Response;
use Zend\Diactoros\Response\InjectContentTypeTrait;
use Zend\Diactoros\Stream;

/**
 * Simple plain text response to use when testing middlewares.
 *
 * Alternative to Diactoros TextResponse: this implementation will not rewind the stream,
 * allowing to easily write new content to the body.
 *
 * That is mostly useful to test middlewares.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SimpleResponse extends Response
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
