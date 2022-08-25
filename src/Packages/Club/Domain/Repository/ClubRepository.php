<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Repository;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;

interface ClubRepository
{
    public function add(Club $club): void;
    public function findOneByName(ClubName $name): ?Club;
    public function find(ClubUuid $id): ?Club;
}