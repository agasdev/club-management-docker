<?php

declare(strict_types=1);

namespace App\Packages\Coach\Infrastructure\Persistence\Doctrine;

use App\Packages\Coach\Domain\Entity\Coach;
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

//    /**
//     * @return Coach[] Returns an array of Coach objects
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

//    public function findOneBySomeField($value): ?Coach
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
