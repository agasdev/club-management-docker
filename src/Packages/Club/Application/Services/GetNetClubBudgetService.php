<?php

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;

class GetNetClubBudgetService
{
    public function __construct(
        private ClubRepository $clubRepository
    )
    {
    }

    public function __invoke(string $clubId, int $budget, ?int $playerSalary = null): int
    {
        $salaryPlayers = $this->clubRepository->getTotalSalaryPlayers($clubId = new ClubUuid($clubId));
        $salaryCoaches = $this->clubRepository->getTotalSalaryCoaches($clubId);
        $totalSalaries = $salaryPlayers + $salaryCoaches;

        if (!is_null($playerSalary)) {
            return $budget - ($totalSalaries + $playerSalary);
        }

        return $budget - $totalSalaries;
    }

}