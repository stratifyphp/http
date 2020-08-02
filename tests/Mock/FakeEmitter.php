<?php
declare(strict_types = 1);

namespace Stratify\Http\Test\Mock;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseInterface;

class FakeEmitter implements EmitterInterface
{
    public ResponseInterface $response;
    public string $output = '';

    public function emit(ResponseInterface $response): bool
    {
        $this->response = $response;
        $this->output = $response->getBody()->__toString();

        return true;
    }
}
