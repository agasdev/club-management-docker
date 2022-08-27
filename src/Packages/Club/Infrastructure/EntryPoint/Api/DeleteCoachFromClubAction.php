<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Exception\CoachNotFoundInClubException;
use App\Packages\Club\Application\Services\DeleteCoachClubService;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCoachFromClubAction extends AbstractApiController
{
    public function __construct(
        private DeleteCoachClubService $deleteCoachClubService
    )
    {
    }

    public function __invoke(string $id, string $coachId): JsonResponse
    {
        try {
            ($this->deleteCoachClubService)($id, $coachId);
        } catch (CoachNotFoundInClubException) {
            return $this->sendError(
                sprintf('Coach with id: %s not found in club', $coachId),
                Response::HTTP_NOT_FOUND
            );
        } catch (ResourceNotFoundException) {
            return $this->sendError(
                sprintf('Club with id: %s not found', $id),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}