<?php

declare(strict_types=1);

namespace App\Packages\Common\Domain\Value;

use App\Packages\Common\Domain\Exception\InvalidCommonNotEmptyStringException;

abstract class CommonNotEmptyString extends CommonString
{
    /**
     * @throws InvalidCommonNotEmptyStringException
     */
    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidCommonNotEmptyStringException();
        }

        parent::__construct($value);
    }
}