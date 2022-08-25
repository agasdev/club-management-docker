<?php

namespace App\Packages\Common\Domain\Exception;

use Throwable;

final class InvalidCommonMixedIntegerPositiveOrZeroException extends DomainException
{
    public function __construct($value, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid integer positive: $value", $code, $previous);
    }
}