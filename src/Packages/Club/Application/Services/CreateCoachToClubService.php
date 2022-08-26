<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Exception\CoachAlreadyExistException;
use App\Packages\Coach\Application\Exception\InvalidCoachFormException;
use App\Packages\Coach\Application\Services\CreateCoachService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class CreateCoachToClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private GetNetClubBudgetService $netClubBudgetService,
        private CreateCoachService $createCoachService
    )
    {
    }

    /**
     * @throws InsufficientBudgetException
     * @throws ResourceNotFoundException
     * @throws CoachAlreadyExistException
     * @throws InvalidCoachFormException
     * @throws InvalidResourceException
     */
    public function __invoke(string $id, Request $request): CoachDto
    {
        $club = ($this->getClubService)($id);

        if (0 > ($this->netClubBudgetService)($id, $club->getBudget()->value(), (int)$request->get('salary'))) {
            throw new InsufficientBudgetException();
        }

        return ($this->createCoachService)($request, $id);
    }
}