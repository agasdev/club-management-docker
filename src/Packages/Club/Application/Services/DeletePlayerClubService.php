<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\PlayerNotFoundInClubException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\SendEmail;
use App\Packages\Player\Domain\Repository\PlayerRepository;

class DeletePlayerClubService
{
    public function __construct(
        private GetClubService $getClubService,
        private GetClubPlayerByIdService $getClubPlayerByIdService,
        private PlayerRepository $playerRepository,
        private SendEmail $sendEmail
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
        $player = ($this->getClubPlayerByIdService)($id, $playerId);

        $club->removePlayer($player);
        $this->playerRepository->update($player);

        ($this->sendEmail)($player, 'delete', $club);
    }

}