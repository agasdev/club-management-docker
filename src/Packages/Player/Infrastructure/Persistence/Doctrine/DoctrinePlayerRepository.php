<?php

declare(strict_types=1);

namespace App\Packages\Player\Infrastructure\Persistence\Doctrine;

use App\Packages\Common\Infrastructure\Repository\DoctrineRepository;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;
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

    public function update(Player $player): void
    {
        $this->doctrineRepository->flush($player);
    }

    public function findOneByEmail(PlayerEmail $email): ?Player
    {
        return $this->doctrineRepository->findOneBy(['email.value' => $email->value()]);
    }
}
