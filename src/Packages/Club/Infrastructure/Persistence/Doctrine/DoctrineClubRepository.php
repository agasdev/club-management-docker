<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\Persistence\Doctrine;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Common\Infrastructure\Repository\DoctrineRepository;
use App\Packages\Player\Domain\Entity\Player;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineClubRepository implements ClubRepository
{
    private DoctrineRepository $doctrineRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrineRepository = new DoctrineRepository($registry, Club::class);
    }

    public function add(Club $club): void
    {
        $this->doctrineRepository->persist($club);
        $this->doctrineRepository->flush($club);
    }

    public function findOneByName(ClubName $name): ?Club
    {
        return $this->doctrineRepository->findOneBy(['name.value' => $name->value()]);
    }

    public function find(ClubUuid $id): ?Club
    {
        return $this->findById($id);
    }

    public function getTotalSalaryPlayers(ClubUuid $id): int
    {
        return (int) $this->doctrineRepository->createQueryBuilder('c')
            ->select('SUM(p.salary.value)')
            ->join(Player::class, 'p', Join::WITH, 'p.club = c')
            ->andWhere('c.id.value = :val')
            ->setParameter('val', $id->value())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalSalaryCoaches(ClubUuid $id): int
    {
        return (int) $this->doctrineRepository->createQueryBuilder('c')
            ->select('SUM(co.salary.value) as salary')
            ->join(Coach::class, 'co', Join::WITH, 'co.club = c')
            ->andWhere('c.id.value = :val')
            ->setParameter('val', $id->value())
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function findById(ClubUuid $id): ?Club
    {
        return $this->doctrineRepository->find($id->value());
    }
}
