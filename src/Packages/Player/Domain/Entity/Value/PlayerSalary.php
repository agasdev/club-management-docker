<?php

namespace App\Packages\Player\Domain\Entity\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonMixedIntegerPositiveOrZeroException;
use App\Packages\Common\Domain\Value\CommonIntegerPositiveOrZero;
use App\Packages\Player\Domain\Exception\InvalidPlayerSalaryException;

class PlayerSalary extends CommonIntegerPositiveOrZero
{
    protected int $value;

    /**
     * @throws InvalidPlayerSalaryException
     */
    public function __construct(int $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonMixedIntegerPositiveOrZeroException) {
            throw new InvalidPlayerSalaryException();
        }
    }
}