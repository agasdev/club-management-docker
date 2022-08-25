<?php

declare(strict_types=1);

namespace App\Packages\Player\Domain\Entity\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;
use App\Packages\Common\Domain\Value\CommonNotEmptyString;
use App\Packages\Player\Domain\Exception\InvalidPlayerEmailException;

class PlayerEmail extends CommonNotEmptyString
{
    protected string $value;

    /**
     * @throws InvalidPlayerEmailException
     */
    public function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidCommonNotEmptyStringException) {
            throw new InvalidPlayerEmailException();
        }
    }
}