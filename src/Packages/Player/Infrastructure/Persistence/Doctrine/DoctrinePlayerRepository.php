<?php

declare(strict_types=1);

namespace App\Packages\Player\Infrastructure\Persistence\Doctrine;

use App\Packages\Common\Infrastructure\Repository\DoctrineRepository;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePlayerRepository implements PlayerRepository
{
    private DoctrineRepository $doctrineRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrineRepository = new DoctrineRepository($registry, Player::class);
    }

    public function add(Player $player): void
    {
        $this->doctrineRepository->persist($player);
        $this->doctrineRepository->flush($player);
    }

//    /**
//     * @return Player[] Returns an array of Player objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Player
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
