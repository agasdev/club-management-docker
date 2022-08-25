<?php

namespace App\Packages\Coach\Application\Services;

use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Exception\CoachAlreadyExistException;
use App\Packages\Coach\Application\Exception\InvalidCoachFormException;
use App\Packages\Coach\Application\Form\Type\CoachFormType;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Entity\Value\CoachCity;
use App\Packages\Coach\Domain\Entity\Value\CoachCountry;
use App\Packages\Coach\Domain\Entity\Value\CoachEmail;
use App\Packages\Coach\Domain\Entity\Value\CoachName;
use App\Packages\Coach\Domain\Entity\Value\CoachSalary;
use App\Packages\Coach\Domain\Entity\Value\CoachUuid;
use App\Packages\Coach\Domain\Exception\InvalidCoachCityException;
use App\Packages\Coach\Domain\Exception\InvalidCoachCountryException;
use App\Packages\Coach\Domain\Exception\InvalidCoachEmailException;
use App\Packages\Coach\Domain\Exception\InvalidCoachNameException;
use App\Packages\Coach\Domain\Exception\InvalidCoachSalaryException;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Services\GetErrorsFromForm;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateCoachService
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private GetErrorsFromForm $getErrorsFromForm,
        private CoachRepository $coachRepository
    )
    {
    }

    /**
     * @throws InvalidCoachFormException
     * @throws CoachAlreadyExistException
     * @throws InvalidResourceException
     */
    public function __invoke(Request $request)
    {
        $coachDto = CoachDto::createEmpty();
        $form = $this->formFactory->create(CoachFormType::class, $coachDto);
        $form->submit(json_decode($request->getContent(), true));
        if (!$form->isValid()) {
            throw new InvalidCoachFormException(json_encode([
                'type' => 'validation_error',
                'errors' => ($this->getErrorsFromForm)($form)
            ]));
        }

        if ($this->coachRepository->findOneByEmail(new CoachEmail($coachDto->email))) {
            throw new CoachAlreadyExistException();
        }

        try {
            $coach = new Coach(
                CoachUuid::new(),
                new CoachName("$coachDto->name", $coachDto->surname),
                new DateTime($coachDto->dateOfBirth),
                new CoachCity($coachDto->city),
                new CoachCountry($coachDto->country),
                new CoachSalary($coachDto->salary),
                new CoachEmail($coachDto->email),
                new DateTimeImmutable()
            );

            $this->coachRepository->add($coach);
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