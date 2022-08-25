<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use App\Packages\Player\Application\Exception\InvalidPlayerFormException;
use App\Packages\Player\Application\Exception\PlayerAlreadyExistException;
use App\Packages\Player\Application\Services\CreatePlayerService;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreatePlayerToClubAction extends AbstractApiController
{

    public function __construct(
        private SerializerInterface $serializer,
        private CreatePlayerService $createPlayerService
    )
    {
    }

    public function __invoke(string $id, Request $request)
    {
        try {
            $response = ($this->createPlayerService)($request, $id);
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