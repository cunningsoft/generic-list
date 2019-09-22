<?php

declare(strict_types=1);

namespace Tests;

final class Dummy
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
