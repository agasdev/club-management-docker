<?php

namespace App\Packages\Player\Domain\Entity\Value;

use App\Packages\Common\Domain\Value\CommonUuid;
use Doctrine\ORM\Mapping as ORM;

class PlayerUuid extends CommonUuid
{
    protected string $value;

    public function __construct(string $value) {
        parent::__construct($value);
    }
}