<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Exception\PlayerNotFoundInClubException;
use App\Packages\Club\Application\Services\DeletePlayerClubService;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeletePlayerFromClubAction extends AbstractApiController
{
    public function __construct(
        private DeletePlayerClubService $deletePlayerClubService
    )
    {
    }

    public function __invoke(string $id, string $playerId): JsonResponse
    {
        try {
            ($this->deletePlayerClubService)($id, $playerId);
        } catch (ResourceNotFoundException) {
            return $this->sendError(
                sprintf('Club with id: %s not found', $id),
                Response::HTTP_NOT_FOUND
            );
        } catch (PlayerNotFoundInClubException) {
            return $this->sendError(
                sprintf('Player with id: %s not found in club', $playerId),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}