<?php

declare(strict_types=1);

namespace App\Packages\Common\Domain\Exception;

final class InvalidCommonUuidException extends DomainException
{
    public function __construct(string $uuid) {
        parent::__construct("Invalid UUID: $uuid");
    }
}