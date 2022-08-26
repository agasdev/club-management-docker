<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\InvalidClubFormException;
use App\Packages\Club\Application\Services\UpdateClubService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UpdateClubAction extends AbstractApiController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UpdateClubService $updateClubService
    )
    {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $response = ($this->updateClubService)($id, $request);
        } catch (InsufficientBudgetException) {
            return $this->sendError('Insufficient budget', Response::HTTP_BAD_REQUEST);
        } catch (InvalidClubFormException $e) {
            return $this->json(json_decode($e->getMessage(), true), Response::HTTP_BAD_REQUEST);
        } catch (InvalidResourceException $e) {
            return $this->sendError('Invalid some club resource', Response::HTTP_BAD_REQUEST);
        } catch (ResourceNotFoundException $e) {
            return $this->sendError(
                sprintf('Club with id: %s not found', $id),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true)
        );
    }

}