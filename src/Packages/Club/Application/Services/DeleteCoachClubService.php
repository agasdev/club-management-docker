<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\CoachNotFoundInClubException;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;

class DeleteCoachClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private CoachRepository $coachRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     * @throws CoachNotFoundInClubException
     */
    public function __invoke(string $id, string $coachId): void
    {
        $club = ($this->getClubService)($id);
        $result = array_filter(
            $club->getCoaches()->toArray(),
            fn($coach) => $coachId === $coach->getId()->value()
        );

        if (empty($result) || !($result[0] instanceof Coach)) {
            throw new CoachNotFoundInClubException();
        }
        $coach = $result[0];

        $club->removeCoach($coach);
        $this->coachRepository->update($coach);
    }

}