<?php

declare(strict_types=1);

namespace App\Packages\Player\Domain\Repository;

use App\Packages\Player\Domain\Entity\Player;

interface PlayerRepository
{
    public function add(Player $player): void;
}