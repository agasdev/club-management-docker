<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Coach\Application\Exception\CoachAlreadyExistException;
use App\Packages\Coach\Application\Exception\InvalidCoachFormException;
use App\Packages\Coach\Application\Services\CreateCoachService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateCoachToClubAction extends AbstractApiController
{
    public function __construct(
        private SerializerInterface $serializer,
        private CreateCoachService $createCoachService
    )
    {
    }

    public function __invoke(string $id, Request $request)
    {
        try {
            $response = ($this->createCoachService)($request, $id);
        } catch (InvalidCoachFormException $e) {
            return $this->json(json_decode($e->getMessage(), true), Response::HTTP_BAD_REQUEST);
        } catch (InvalidResourceException) {
            return $this->sendError('Invalid some coach resource', Response::HTTP_BAD_REQUEST);
        } catch (CoachAlreadyExistException) {
            return $this->sendError('Coach already exist', Response::HTTP_CONFLICT);
        }

        return $this->json(
            json_decode($this->serializer->serialize($response, 'json'), true)
        );
    }

}