<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Repository;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Entity\Value\CoachEmail;
use App\Packages\Coach\Domain\Entity\Value\CoachUuid;

interface CoachRepository
{
    public function add(Coach $coach): void;
    public function update(Coach $coach): void;
    public function find(CoachUuid $id): ?Coach;
    public function findOneByEmail(CoachEmail $email): ?Coach;
    public function findOneByClubIdAndId(ClubUuid $clubId, CoachUuid $id): ?Coach;
}