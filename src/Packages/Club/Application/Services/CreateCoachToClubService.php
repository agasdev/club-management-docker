<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
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
        private GetNetClubBudgetService $getNetClubBudgetService,
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
     * @throws RequiredSalaryFieldException
     */
    public function __invoke(string $id, Request $request): CoachDto
    {
        $club = ($this->getClubService)($id);

        if (empty($salary = $request->get('salary'))) {
            throw new RequiredSalaryFieldException();
        }

        if (0 > ($this->getNetClubBudgetService)($id, $club->getBudget()->value(), (int)$salary)) {
            throw new InsufficientBudgetException();
        }

        return ($this->createCoachService)($request, $id);
    }
}