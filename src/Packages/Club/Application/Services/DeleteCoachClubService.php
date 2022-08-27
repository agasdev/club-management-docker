<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\CoachNotFoundInClubException;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;

class DeleteCoachClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private GetClubCoachByIdService $getClubCoachByIdService,
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
        $coach = ($this->getClubCoachByIdService)($id, $coachId);

        $club->removeCoach($coach);
        $this->coachRepository->update($coach);
    }

}