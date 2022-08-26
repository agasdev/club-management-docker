<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Exception\PlayerAlreadyExistException;
use App\Packages\Player\Application\Services\CreatePlayerService;
use Symfony\Component\HttpFoundation\Request;

class CreatePlayerToClubService
{
    public function __construct(
        private ClubRepository $clubRepository,
        private GetNetClubBudgetService $netClubBudgetService,
        private CreatePlayerService $createPlayerService
    )
    {
    }

    /**
     * @throws InsufficientBudgetException
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     * @throws InvalidPlayerFormException
     * @throws PlayerAlreadyExistException
     */
    public function __invoke(string $id, Request $request): PlayerDto
    {
        try {
            $club = $this->clubRepository->find(new ClubUuid($id));

            if (!$club) {
                throw new ResourceNotFoundException();
            }
        } catch (InvalidCommonUuidException) {
            throw new ResourceNotFoundException();
        }

        if (0 > ($this->netClubBudgetService)($id, $club->getBudget()->value(), (int)$request->get('salary'))) {
            throw new InsufficientBudgetException();
        }

        return ($this->createPlayerService)($request, $id);
    }

}