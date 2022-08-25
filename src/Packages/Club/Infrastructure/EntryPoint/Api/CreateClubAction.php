<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\EntryPoint\Api;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Common\Infrastructure\EntryPoint\Api\AbstractApiController;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CreateClubAction extends AbstractApiController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
//        $date = new \DateTimeImmutable();
//        $data = new Club('asass', 'Madrid', 'Madrid', 'Spain', 1000000, $date);
//        return $this->json(
//            json_decode($this->serializer->serialize($data, 'json'), true)
//        );
    }
}