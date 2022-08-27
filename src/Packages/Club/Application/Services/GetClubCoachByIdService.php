<?php

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\CoachNotFoundInClubException;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Entity\Value\CoachUuid;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;

class GetClubCoachByIdService
{
    public function __construct(
        private CoachRepository $coachRepository
    )
    {
    }

    /**
     * @throws CoachNotFoundInClubException
     */
    public function __invoke(string $id, string $coachId): Coach
    {
        try {
            $coach = $this->coachRepository->findOneByClubIdAndId(new ClubUuid($id), new CoachUuid($coachId));

            if (!$coach) {
                throw new CoachNotFoundInClubException();
            }
        } catch (InvalidCommonUuidException) {
            throw new CoachNotFoundInClubException();
        }

        return $coach;
    }
}