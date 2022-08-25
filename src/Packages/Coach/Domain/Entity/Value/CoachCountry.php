<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity\Value;

use App\Packages\Coach\Domain\Exception\InvalidCoachCountryException;
use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;

class CoachCountry extends CommonNotEmptyString
{
    protected string $value;

    /**
     * @throws InvalidCoachCountryException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidCoachCountryException();
        }
    }
}