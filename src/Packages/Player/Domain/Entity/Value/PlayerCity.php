<?php

namespace App\Packages\Player\Domain\Entity\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;
use App\Packages\Player\Domain\Exception\InvalidPlayerCityException;

class PlayerCity extends CommonNotEmptyString
{
    protected string $value;

    /**
     * @throws InvalidPlayerCityException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidPlayerCityException();
        }
    }
}