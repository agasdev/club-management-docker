<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity\Value;

use App\Packages\Club\Domain\Exception\InvalidClubCountryException;
use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;

class ClubCountry extends CommonNotEmptyString
{
    protected string $value;

    /**
     * @throws InvalidClubCountryException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidClubCountryException();
        }
    }
}