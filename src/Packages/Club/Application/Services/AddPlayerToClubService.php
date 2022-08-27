<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Form\Type\PlayerFormType;
use App\Packages\Player\Application\Services\GetPlayerService;
use App\Packages\Player\Domain\Entity\Value\PlayerCity;
use App\Packages\Player\Domain\Entity\Value\PlayerCountry;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Entity\Value\PlayerName;
use App\Packages\Player\Domain\Entity\Value\PlayerSalary;
use App\Packages\Player\Domain\Exception\InvalidPlayerCityException;
use App\Packages\Player\Domain\Exception\InvalidPlayerCountryException;
use App\Packages\Player\Domain\Exception\InvalidPlayerEmailException;
use App\Packages\Player\Domain\Exception\InvalidPlayerNameException;
use App\Packages\Player\Domain\Exception\InvalidPlayerSalaryException;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class AddPlayerToClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private GetPlayerService $getPlayerService,
        private GetNetClubBudgetService $getNetClubBudgetService,
        private CreateAndValidateForm $createAndValidateForm,
        private PlayerRepository $playerRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     * @throws RequiredSalaryFieldException
     * @throws InsufficientBudgetException
     * @throws InvalidPlayerFormException
     * @throws InvalidResourceException
     */
    public function __invoke(string $id, string $playerId, Request $request): PlayerDto
    {
        $club = ($this->getClubService)($id);
        $player = ($this->getPlayerService)($playerId);

        if (empty($salary = $request->get('salary'))) {
            throw new RequiredSalaryFieldException();
        }

        if (0 > ($this->getNetClubBudgetService)($id, $club->getBudget()->value(), (int)$salary)) {
            throw new InsufficientBudgetException();
        }

        $playerDto = PlayerDto::assemble($player);

        try {
            $playerDto = ($this->createAndValidateForm)(
                $request,
                PlayerFormType::class,
                $playerDto,
                $request->getMethod() != 'PATCH'
            );
        } catch (InvalidResourceException $e) {
            throw new InvalidPlayerFormException($e->getMessage());
        }

        try {
            $player->update(
                new PlayerName($playerDto->name, $playerDto->surname),
                new DateTime($playerDto->dateOfBirth),
                new PlayerCity($playerDto->city),
                new PlayerCountry($playerDto->country),
                new PlayerSalary($playerDto->salary),
                new PlayerEmail($playerDto->email),
                $club
            );

            $this->playerRepository->update($player);
        } catch (
            InvalidPlayerNameException|
            InvalidPlayerCityException|
            InvalidPlayerCountryException|
            InvalidPlayerSalaryException|
            InvalidPlayerEmailException
        ) {
            throw new InvalidResourceException();
        }

        return PlayerDto::assemble($player);
    }

}