<?php

namespace App\Packages\Common\Domain\Exception;

final class InvalidCommonNotEmptyStringException extends DomainException
{
    public function __construct()
    {
        parent::__construct("String must not be empty");
    }
}