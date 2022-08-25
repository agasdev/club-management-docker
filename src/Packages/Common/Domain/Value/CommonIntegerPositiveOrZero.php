<?php

namespace App\Packages\Common\Domain\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonMixedIntegerPositiveOrZeroException;

abstract class CommonIntegerPositiveOrZero extends CommonInteger
{
    /**
     * @throws InvalidCommonMixedIntegerPositiveOrZeroException
     */
    public function __construct($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidCommonMixedIntegerPositiveOrZeroException($value);
        }

        if ($value <= -1) {
            throw new InvalidCommonMixedIntegerPositiveOrZeroException($value);
        }

        parent::__construct($value);
    }
}