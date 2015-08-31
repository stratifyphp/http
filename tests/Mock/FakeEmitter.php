<?php

namespace Stratify\Http\Test\Mock;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmitterInterface;

class FakeEmitter implements EmitterInterface
{
    public $response;
    public $output;

    public function emit(ResponseInterface $response)
    {
        $this->response = $response;
        $this->output = $response->getBody()->__toString();
    }
}
