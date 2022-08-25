<?php

declare(strict_types=1);

namespace App\Packages\Club\Infrastructure\Persistence\Doctrine;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Infrastructure\Repository\DoctrineRepository;
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
        return $this->doctrineRepository->find($id->value());
    }

//    /**
//     * @return Club[] Returns an array of Club objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Club
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
