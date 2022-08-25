<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity\Value;

use App\Packages\Common\Domain\Value\CommonUuid;

/**
 * @method static ClubUuid new()
 */
class ClubUuid extends CommonUuid
{
    protected string $value;

    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}