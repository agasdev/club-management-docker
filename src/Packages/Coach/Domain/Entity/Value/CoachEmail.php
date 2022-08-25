<?php

namespace App\Packages\Coach\Domain\Entity\Value;

use App\Packages\Coach\Domain\Exception\InvalidCoachEmailException;
use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;

class CoachEmail extends CommonNotEmptyString
{
    protected string $value;

    /**
     * @throws InvalidCoachEmailException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidCoachEmailException();
        }
    }
}