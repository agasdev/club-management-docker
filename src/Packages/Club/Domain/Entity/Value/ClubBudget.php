<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity\Value;

use App\Packages\Club\Domain\Exception\InvalidClubBudgetException;
use App\Packages\Common\Domain\Exception\InvalidCommonMixedIntegerPositiveOrZeroException;
use App\Packages\Common\Domain\Value\CommonIntegerPositiveOrZero;

class ClubBudget extends CommonIntegerPositiveOrZero
{
    protected int $value;

    /**
     * @throws InvalidClubBudgetException
     */
    public function __construct(int $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonMixedIntegerPositiveOrZeroException) {
            throw new InvalidClubBudgetException();
        }
    }
}