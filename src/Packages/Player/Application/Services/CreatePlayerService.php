<?php

declare(strict_types=1);

namespace App\Packages\Player\Application\Services;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Packages\Common\Application\Services\SendEmail;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Exception\PlayerAlreadyExistException;
use App\Packages\Player\Application\Form\Type\PlayerFormType;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerCity;
use App\Packages\Player\Domain\Entity\Value\PlayerCountry;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Entity\Value\PlayerName;
use App\Packages\Player\Domain\Entity\Value\PlayerSalary;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;
use App\Packages\Player\Domain\Exception\InvalidPlayerCityException;
use App\Packages\Player\Domain\Exception\InvalidPlayerCountryException;
use App\Packages\Player\Domain\Exception\InvalidPlayerEmailException;
use App\Packages\Player\Domain\Exception\InvalidPlayerNameException;
use App\Packages\Player\Domain\Exception\InvalidPlayerSalaryException;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class CreatePlayerService
{

    public function __construct(
        private CreateAndValidateForm $createAndValidateForm,
        private PlayerRepository $playerRepository,
        private ClubRepository $clubRepository,
        private SendEmail $sendEmail
    )
    {
    }

    /**
     * @throws InvalidPlayerFormException
     * @throws InvalidResourceException
     * @throws PlayerAlreadyExistException
     */
    public function __invoke(Request $request, ?string $clubId = null): PlayerDto
    {
        try {
            $playerDto = ($this->createAndValidateForm)(
                $request,
                PlayerFormType::class,
                PlayerDto::createEmpty()
            );
        } catch (InvalidResourceException $e) {
            throw new InvalidPlayerFormException($e->getMessage());
        }

        if ($this->playerRepository->findOneByEmail(new PlayerEmail($playerDto->email))) {
            throw new PlayerAlreadyExistException();
        }

        try {
            $player = new Player(
                PlayerUuid::new(),
                new PlayerName($playerDto->name, $playerDto->surname),
                new DateTime($playerDto->dateOfBirth),
                new PlayerCity($playerDto->city),
                new PlayerCountry($playerDto->country),
                !is_null($playerDto->salary) ? new PlayerSalary($playerDto->salary) : null,
                new PlayerEmail($playerDto->email),
                $clubId ? ($this->clubRepository->find(new ClubUuid($clubId))) : null
            );

            $this->playerRepository->add($player);
        } catch (
            InvalidPlayerNameException|
            InvalidPlayerCityException|
            InvalidPlayerCountryException|
            InvalidPlayerSalaryException|
            InvalidPlayerEmailException
        ) {
            throw new InvalidResourceException();
        }

        if (!is_null($clubId)) {
            ($this->sendEmail)($player, 'add');
        }

        return PlayerDto::assemble($player);
    }
}