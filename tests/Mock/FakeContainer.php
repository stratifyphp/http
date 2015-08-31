<?php

namespace Stratify\Http\Test\Mock;

use Interop\Container\ContainerInterface;

class FakeContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $entries;

    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function get($id)
    {
        return $this->entries[$id];
    }

    public function has($id)
    {
        return array_key_exists($id, $this->entries);
    }
}
