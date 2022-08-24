<?php

declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DoctrineRepository extends ServiceEntityRepository
{
    public function persist($entity): void
    {
        $this->_em->persist($entity);
    }

    public function flush($entity): void
    {
        $this->_em->flush($entity);
    }

    public function remove($entity): void
    {
        $this->_em->remove($entity);
    }
}