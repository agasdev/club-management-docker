<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity\Value;

use App\Packages\Club\Domain\Exception\InvalidClubCityException;
use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;

class ClubCity extends CommonNotEmptyString
{
    protected string $value;

    /**
     * @throws InvalidClubCityException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidClubCityException();
        }
    }
}