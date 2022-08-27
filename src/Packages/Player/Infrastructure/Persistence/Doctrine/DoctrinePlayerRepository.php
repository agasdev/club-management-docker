<?php

declare(strict_types=1);

namespace App\Packages\Player\Infrastructure\Persistence\Doctrine;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Common\Infrastructure\Repository\DoctrineRepository;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function findByClubId(ClubUuid $clubId, int $page, int $itemsPerPage, string $search): array
    {
        $queryBuilder = $this->doctrineRepository->createQueryBuilder('p')
            ->select([
                'p.id.value AS id',
                'p.name.name AS name',
                'p.name.surname AS surname',
                'p.dateOfBirth',
                'p.city.value AS city',
                'p.country.value AS country',
                'p.salary.value AS salary',
                'p.email.value AS email',
                "CONCAT(
                        COALESCE(p.name.name, ''),
                        COALESCE(p.name.surname, ''),
                        COALESCE(p.city.value, ''),
                        COALESCE(p.country.value, ''),
                        COALESCE(p.email.value, ''),
                        COALESCE(p.salary.value, '')
                    ) AS searchableFields"
            ])
            ->andWhere('p.club = :clubId')
            ->setParameter('clubId', $clubId->value());

        $this->addSearchQuery($queryBuilder, $search);

        $totalMatchedAttendees = count($queryBuilder->getQuery()->execute());

        if (0 !== $itemsPerPage && 0 !== $page) {
            $queryBuilder
                ->setFirstResult($itemsPerPage * ($page - 1))
                ->setMaxResults($itemsPerPage);
        }

        return [
            array_map(
                fn(array $row) => $this->rowToPlayerDto($row),
                $queryBuilder->getQuery()->execute()
            ),
            $totalMatchedAttendees
        ];
    }

    private function addSearchQuery(QueryBuilder $queryBuilder, string $search): void
    {
        if (empty($search)) {
            return;
        }

        $words = explode(' ', $search);
        foreach ($words as $wordIndex => $word) {
            $queryBuilder->andHaving( // We can't use WHERE because the field searchableFields is an alias
                "searchableFields LIKE :word_$wordIndex"
            );
            $queryBuilder->setParameter("word_$wordIndex", "%$word%");
        }
    }

    private function rowToPlayerDto(array $row): PlayerDto
    {
        return new PlayerDto(
            $row['id'],
            $row['name'],
            $row['surname'],
            $row['dateOfBirth']->format('d/m/Y'),
            $row['city'],
            $row['country'],
            (int)$row['salary'],
            $row['email'],
        );
    }
}
