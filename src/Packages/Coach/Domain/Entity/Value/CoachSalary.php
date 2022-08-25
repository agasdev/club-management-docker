<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity\Value;

use App\Packages\Coach\Domain\Exception\InvalidCoachSalaryException;
use App\Packages\Common\Domain\Exception\InvalidCommonMixedIntegerPositiveOrZeroException;
use App\Packages\Common\Domain\Value\CommonIntegerPositiveOrZero;

class CoachSalary extends CommonIntegerPositiveOrZero
{
    protected int $value;

    /**
     * @throws InvalidCoachSalaryException
     */
    public function __construct(int $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonMixedIntegerPositiveOrZeroException) {
            throw new InvalidCoachSalaryException();
        }
    }
}