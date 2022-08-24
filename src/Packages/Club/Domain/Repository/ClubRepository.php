<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Repository;

use App\Packages\Club\Domain\Entity\Club;

interface ClubRepository
{
    public function add(Club $club): void;
}