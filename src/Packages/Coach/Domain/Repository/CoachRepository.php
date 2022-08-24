<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Repository;

use App\Packages\Coach\Domain\Entity\Coach;

interface CoachRepository
{
    public function add(Coach $coach): void;
}