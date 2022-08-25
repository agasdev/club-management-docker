<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;
use App\Packages\Coach\Domain\Exception\InvalidCoachCityException;

class CoachCity extends CommonNotEmptyString
{
    /**
     * @throws InvalidCoachCityException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidCoachCityException();
        }
    }
}