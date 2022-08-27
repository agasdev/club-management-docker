<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\PlayerNotFoundInClubException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Repository\PlayerRepository;

class DeletePlayerClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private PlayerRepository $playerRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     * @throws PlayerNotFoundInClubException
     */
    public function __invoke(string $id, string $playerId): void
    {
        $club = ($this->getClubService)($id);
        $result = array_filter(
            $club->getPlayers()->toArray(),
            fn($player) => $playerId === $player->getId()->value()
        );

        if (empty($result) || !($result[0] instanceof Player)) {
            throw new PlayerNotFoundInClubException();
        }
        $player = $result[0];

        $club->removePlayer($player);
        $this->playerRepository->update($player);
    }

}