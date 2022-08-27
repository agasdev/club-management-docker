<?php

declare(strict_types=1);

namespace App\Packages\Player\Infrastructure\EntryPoint\Api;

use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Exception\PlayerAlreadyExistException;
use App\Packages\Player\Application\Services\CreatePlayerService;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreatePlayerAction extends AbstractApiController
{
    public function __construct(
        private SerializerInterface $serializer,
        private CreatePlayerService $createPlayerService
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $response = ($this->createPlayerService)($request);
        } catch (InvalidPlayerFormException $e) {
            return $this->json(json_decode($e->getMessage(), true), Response::HTTP_BAD_REQUEST);
        } catch (InvalidResourceException) {
            return $this->sendError('Invalid some player resource', Response::HTTP_BAD_REQUEST);
        } catch (PlayerAlreadyExistException) {
            return $this->sendError('Player already exist', Response::HTTP_CONFLICT);
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true)
        );
    }
}