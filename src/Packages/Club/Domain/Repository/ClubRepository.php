<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Repository;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use Doctrine\Common\Collections\Collection;

interface ClubRepository
{
    public function add(Club $club): void;
    public function update(Club $club): void;
    public function findOneByName(ClubName $name): ?Club;
    public function find(ClubUuid $id): ?Club;
    public function getTotalSalaryPlayers(ClubUuid $id): int;
    public function getTotalSalaryCoaches(ClubUuid $id): int;
}