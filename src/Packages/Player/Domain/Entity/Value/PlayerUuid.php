<?php

declare(strict_types=1);

namespace App\Packages\Player\Domain\Entity\Value;

use App\Packages\Common\Domain\Value\CommonUuid;

/**
 * @method static PlayerUuid new()
 */
class PlayerUuid extends CommonUuid
{
    protected string $value;

    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}