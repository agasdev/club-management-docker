<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity\Value;

use App\Packages\Common\Domain\Value\CommonUuid;

class ClubUuid extends CommonUuid
{
    protected string $value;

    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}