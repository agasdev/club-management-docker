<?php

declare(strict_types=1);

namespace App\Packages\Coach\Infrastructure\Persistence\Doctrine;

use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Entity\Value\CoachEmail;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Infrastructure\Repository\DoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCoachRepository implements CoachRepository
{
    private DoctrineRepository $doctrineRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrineRepository = new DoctrineRepository($registry, Coach::class);
    }

    public function add(Coach $coach): void
    {
        $this->doctrineRepository->persist($coach);
        $this->doctrineRepository->flush($coach);
    }

    public function update(Coach $coach): void
    {
        $this->doctrineRepository->flush($coach);
    }

    public function findOneByEmail(CoachEmail $email): ?Coach
    {
        return $this->doctrineRepository->findOneBy(['email.value' => $email->value()]);
    }
}
