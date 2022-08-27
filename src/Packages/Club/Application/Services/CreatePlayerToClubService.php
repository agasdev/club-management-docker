<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Exception\PlayerAlreadyExistException;
use App\Packages\Player\Application\Services\CreatePlayerService;
use Symfony\Component\HttpFoundation\Request;

class CreatePlayerToClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private GetNetClubBudgetService $getNetClubBudgetService,
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
     * @throws RequiredSalaryFieldException
     */
    public function __invoke(string $id, Request $request): PlayerDto
    {
        $club = ($this->getClubService)($id);

        if (empty($salary = $request->get('salary'))) {
            throw new RequiredSalaryFieldException();
        }

        if (0 > ($this->getNetClubBudgetService)($id, $club->getBudget()->value(), (int)$salary)) {
            throw new InsufficientBudgetException();
        }

        return ($this->createPlayerService)($request, $id);
    }

}