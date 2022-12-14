<?php

declare(strict_types=1);

namespace App\Packages\Player\Domain\Repository;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;

interface PlayerRepository
{
    public function add(Player $player): void;
    public function update(Player $player): void;
    public function find(PlayerUuid $id): ?Player;
    public function findOneByEmail(PlayerEmail $email): ?Player;
    public function findByClubId(ClubUuid $clubId, int $page, int $itemsPerPage, string $search): array;
    public function findOneByClubIdAndId(ClubUuid $clubId, PlayerUuid $id): ?Player;
}