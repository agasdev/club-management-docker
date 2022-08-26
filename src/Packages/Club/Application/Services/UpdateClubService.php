<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\InvalidClubFormException;
use App\Packages\Club\Application\Form\Type\ClubFormType;
use App\Packages\Club\Domain\Entity\Value\ClubBudget;
use App\Packages\Club\Domain\Entity\Value\ClubCity;
use App\Packages\Club\Domain\Entity\Value\ClubCountry;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Exception\InvalidClubBudgetException;
use App\Packages\Club\Domain\Exception\InvalidClubCityException;
use App\Packages\Club\Domain\Exception\InvalidClubCountryException;
use App\Packages\Club\Domain\Exception\InvalidClubNameException;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use Symfony\Component\HttpFoundation\Request;

class UpdateClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private CreateAndValidateForm $createAndValidateForm,
        private GetNetClubBudgetService $getNetClubBudgetService,
        private ClubRepository $clubRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     * @throws InvalidClubFormException
     * @throws InsufficientBudgetException
     * @throws InvalidResourceException
     */
    public function __invoke(string $id, Request $request): ClubDto
    {
        $club = ($this->getClubService)($id);
        $clubDto = ClubDto::assemble($club);

        try {
            $updatedClubDto = ($this->createAndValidateForm)(
                $request,
                ClubFormType::class,
                $clubDto,
                $request->getMethod() != 'PATCH'
            );
        } catch (InvalidResourceException $e) {
            throw new InvalidClubFormException($e->getMessage());
        }

        if ($club->getBudget()->value() !== $updatedClubDto->budget) {
            $netClubBudget = ($this->getNetClubBudgetService)(
                $id,
                $updatedClubDto->budget
            );

            if (0 > $netClubBudget) {
                throw new InsufficientBudgetException();
            }
        }

        try {
            $club->update(
                new ClubName($clubDto->name),
                new ClubCity($clubDto->city),
                new ClubCountry($clubDto->country),
                new ClubBudget($clubDto->budget)
            );

            $this->clubRepository->update($club);
        } catch (InvalidClubNameException|
            InvalidClubCityException|
            InvalidClubCountryException|
            InvalidClubBudgetException
        ) {
            throw new InvalidResourceException();
        }

        return ClubDto::assemble($club);
    }

}