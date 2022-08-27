<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Domain\Repository\PlayerRepository;

class GetClubPlayersService
{
    public function __construct(
        private GetClubService $getClubService,
        private PlayerRepository $playerRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(string $id): array
    {
        $club = ($this->getClubService)($id);

        $players = $this->playerRepository->findByClubId(new ClubUuid($id));
        $playersDto = array_map(
            fn($player) => PlayerDto::assemble($player),
            $players
        );

        return $playersDto;
    }

}