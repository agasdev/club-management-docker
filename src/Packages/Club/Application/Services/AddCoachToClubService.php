<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Exception\InvalidCoachFormException;
use App\Packages\Coach\Application\Form\Type\CoachFormType;
use App\Packages\Coach\Application\Services\GetCoachService;
use App\Packages\Coach\Domain\Entity\Value\CoachCity;
use App\Packages\Coach\Domain\Entity\Value\CoachCountry;
use App\Packages\Coach\Domain\Entity\Value\CoachEmail;
use App\Packages\Coach\Domain\Entity\Value\CoachName;
use App\Packages\Coach\Domain\Entity\Value\CoachSalary;
use App\Packages\Coach\Domain\Exception\InvalidCoachCityException;
use App\Packages\Coach\Domain\Exception\InvalidCoachCountryException;
use App\Packages\Coach\Domain\Exception\InvalidCoachEmailException;
use App\Packages\Coach\Domain\Exception\InvalidCoachNameException;
use App\Packages\Coach\Domain\Exception\InvalidCoachSalaryException;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class AddCoachToClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private GetCoachService $getCoachService,
        private GetNetClubBudgetService $getNetClubBudgetService,
        private CreateAndValidateForm $createAndValidateForm,
        private CoachRepository $coachRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     * @throws RequiredSalaryFieldException
     * @throws InsufficientBudgetException
     * @throws InvalidCoachFormException
     * @throws InvalidResourceException
     */
    public function __invoke(string $id, string $coachId, Request $request)
    {
        $club = ($this->getClubService)($id);
        $coach = ($this->getCoachService)($coachId);

        if (empty($salary = $request->get('salary'))) {
            throw new RequiredSalaryFieldException();
        }

        if (0 > ($this->getNetClubBudgetService)($id, $club->getBudget()->value(), (int)$salary)) {
            throw new InsufficientBudgetException();
        }

        $coachDto = CoachDto::assemble($coach);

        try {
            $coachDto = ($this->createAndValidateForm)(
                $request,
                CoachFormType::class,
                $coachDto,
                $request->getMethod() != 'PATCH'
            );
        } catch (InvalidResourceException $e) {
            throw new InvalidCoachFormException($e->getMessage());
        }

        try {
            $coach->update(
                new CoachName("$coachDto->name", $coachDto->surname),
                new DateTime($coachDto->dateOfBirth),
                new CoachCity($coachDto->city),
                new CoachCountry($coachDto->country),
                new CoachSalary($coachDto->salary),
                new CoachEmail($coachDto->email),
                $club
            );

            $this->coachRepository->update($coach);
        } catch (
            InvalidCoachNameException|
            InvalidCoachCityException|
            InvalidCoachCountryException|
            InvalidCoachSalaryException|
            InvalidCoachEmailException
        ) {
            throw new InvalidResourceException();
        }

        return CoachDto::assemble($coach);
    }

}