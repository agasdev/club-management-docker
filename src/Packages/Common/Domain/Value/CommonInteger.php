<?php

declare(strict_types=1);

namespace App\Packages\Common\Domain\Value;

abstract class CommonInteger
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}