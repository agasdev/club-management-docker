<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Exception\ClubAlreadyExistException;
use App\Packages\Club\Application\Exception\InvalidClubFormException;
use App\Packages\Club\Application\Services\CreateClubService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateClubAction extends AbstractApiController
{
    public function __construct(
        private SerializerInterface $serializer,
        private CreateClubService $createClubService
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $response = ($this->createClubService)($request);
        } catch (ClubAlreadyExistException) {
            return $this->sendError('Club already exist', Response::HTTP_CONFLICT);
        } catch (InvalidClubFormException $e) {
            return $this->json(json_decode($e->getMessage(), true), Response::HTTP_BAD_REQUEST);
        } catch (InvalidResourceException $e) {
            return $this->sendError('Invalid some club resource', Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true)
        );
    }
}