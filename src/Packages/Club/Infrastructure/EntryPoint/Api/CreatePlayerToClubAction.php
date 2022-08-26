<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Services\CreatePlayerToClubService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Exception\PlayerAlreadyExistException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreatePlayerToClubAction extends AbstractApiController
{

    public function __construct(
        private SerializerInterface $serializer,
        private CreatePlayerToClubService $createPlayerToClubService,
    )
    {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $response = ($this->createPlayerToClubService)($id, $request);
        } catch (InvalidPlayerFormException $e) {
            return $this->json(json_decode($e->getMessage(), true), Response::HTTP_BAD_REQUEST);
        } catch (InvalidResourceException) {
            return $this->sendError('Invalid some player resource', Response::HTTP_BAD_REQUEST);
        } catch (PlayerAlreadyExistException) {
            return $this->sendError('Player already exist', Response::HTTP_CONFLICT);
        } catch (InsufficientBudgetException) {
            return $this->sendError('Club don\'t have enough budget', Response::HTTP_BAD_REQUEST);
        } catch (ResourceNotFoundException) {
            return $this->sendError(sprintf('Club with id: %s not found', $id), Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true)
        );
    }


}