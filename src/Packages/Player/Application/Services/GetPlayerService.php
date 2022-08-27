<?php

declare(strict_types=1);

namespace App\Packages\Player\Application\Services;

use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Domain\Exception\InvalidCommonUuidException;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;
use App\Packages\Player\Domain\Repository\PlayerRepository;

class GetPlayerService
{
    public function __construct(
        private PlayerRepository $playerRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(string $id): Player
    {
        try {
            $player = $this->playerRepository->find(new PlayerUuid($id));

            if (!$player) {
                throw new ResourceNotFoundException();
            }
        } catch (InvalidCommonUuidException) {
            throw new ResourceNotFoundException();
        }

        return $player;
    }
}