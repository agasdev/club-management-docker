<?php

declare(strict_types=1);

namespace App\Packages\Coach\Application\Services;

use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Entity\Value\CoachUuid;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;

class GetCoachService
{
    public function __construct(
        private CoachRepository $coachRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(string $id): Coach
    {
        try {
            $coach = $this->coachRepository->find(new CoachUuid($id));

            if (!$coach) {
                throw new ResourceNotFoundException();
            }
        } catch (InvalidCommonUuidException) {
            throw new ResourceNotFoundException();
        }

        return $coach;
    }

}