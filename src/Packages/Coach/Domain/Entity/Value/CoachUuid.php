<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity\Value;

use App\Packages\Common\Domain\Value\CommonUuid;

/**
 * @method static CoachUuid new()
 */
class CoachUuid extends CommonUuid
{
    protected string $value;

    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}