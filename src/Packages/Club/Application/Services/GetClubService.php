<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;

class GetClubService
{
    public function __construct(
        private ClubRepository $clubRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(string $id): Club
    {
        try {
            $club = $this->clubRepository->find(new ClubUuid($id));

            if (!$club) {
                throw new ResourceNotFoundException();
            }
        } catch (InvalidCommonUuidException) {
            throw new ResourceNotFoundException();
        }

        return $club;
    }

}