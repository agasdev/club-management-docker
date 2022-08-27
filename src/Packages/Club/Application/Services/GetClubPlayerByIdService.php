<?php

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\PlayerNotFoundInClubException;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;
use App\Packages\Player\Domain\Repository\PlayerRepository;

class GetClubPlayerByIdService
{
    public function __construct(
        private PlayerRepository $playerRepository
    )
    {
    }

    /**
     * @throws PlayerNotFoundInClubException
     */
    public function __invoke(string $id, string $playerId): Player
    {
        try {
            $player = $this->playerRepository->findOneByClubIdAndId(new ClubUuid($id), new PlayerUuid($playerId));

            if (!$player) {
                throw new PlayerNotFoundInClubException();
            }
        } catch (InvalidCommonUuidException) {
            throw new PlayerNotFoundInClubException();
        }

        return $player;
    }
}