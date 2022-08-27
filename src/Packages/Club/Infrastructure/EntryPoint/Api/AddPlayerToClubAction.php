<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Club\Application\Services\AddPlayerToClubService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AddPlayerToClubAction extends AbstractApiController
{
    public function __construct(
        private SerializerInterface $serializer,
        private AddPlayerToClubService $addPlayerToClubService
    )
    {
    }

    public function __invoke(string $id, string $playerId, Request $request): JsonResponse
    {
        try {
            $response = ($this->addPlayerToClubService)($id, $playerId, $request);
        } catch (InsufficientBudgetException) {
            return $this->sendError('Club don\'t have enough budget', Response::HTTP_BAD_REQUEST);
        } catch (RequiredSalaryFieldException) {
            return $this->sendError('Required salary field', Response::HTTP_BAD_REQUEST);
        } catch (ResourceNotFoundException) {
            return $this->sendError(sprintf('Club with id: %s not found', $id), Response::HTTP_NOT_FOUND);
        } catch (InvalidResourceException) {
            return $this->sendError('Invalid some player resource', Response::HTTP_BAD_REQUEST);
        } catch (InvalidPlayerFormException $e) {
            return $this->json(json_decode($e->getMessage(), true), Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true),
            Response::HTTP_OK
        );
    }

}