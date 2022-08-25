<?php

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Exception\ClubAlreadyExistException;
use App\Packages\Club\Application\Exception\InvalidClubFormException;
use App\Packages\Club\Application\Form\Type\ClubFormType;
use App\Packages\Club\Domain\Entity\Club;
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
use App\Packages\Common\Application\Services\GetErrorsFromForm;
use DateTimeImmutable;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateClubService
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private GetErrorsFromForm $getErrorsFromForm,
        private ClubRepository $clubRepository
    )
    {
    }

    /**
     * @throws InvalidClubFormException
     * @throws ClubAlreadyExistException
     * @throws InvalidResourceException
     */
    public function __invoke(Request $request): ClubDto
    {
        $clubDto = ClubDto::createEmpty();
        $form = $this->formFactory->create(ClubFormType::class, $clubDto);
        $form->submit(json_decode($request->getContent(), true));
        if (!$form->isValid()) {
            throw new InvalidClubFormException(json_encode([
                'type' => 'validation_error',
                'errors' => ($this->getErrorsFromForm)($form)
            ]));
        }

        if ($this->clubRepository->findOneByName(new ClubName($clubDto->name))) {
            throw new ClubAlreadyExistException();
        }

        try {
            $club = new Club(
                ClubUuid::new(),
                new ClubName($clubDto->name),
                new ClubCity($clubDto->city),
                new ClubCountry($clubDto->country),
                new ClubBudget($clubDto->budget),
                new DateTimeImmutable()
            );

            $this->clubRepository->add($club);
        } catch (
            InvalidClubNameException|
            InvalidClubCityException|
            InvalidClubCountryException|
            InvalidClubBudgetException
        ) {
            throw new InvalidResourceException();
        }

        return ClubDto::assemble($club);
    }

}