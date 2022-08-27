<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Services\GetClubPlayersService;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListClubPlayersAction extends AbstractApiController
{
    public function __construct(
        private SerializerInterface $serializer,
        private GetClubPlayersService $getClubPlayersService
    )
    {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $response = ($this->getClubPlayersService)($id, $request);
        } catch (ResourceNotFoundException) {
            return $this->sendError(sprintf('Club with id: %s not found', $id), Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true)
        );
    }

}