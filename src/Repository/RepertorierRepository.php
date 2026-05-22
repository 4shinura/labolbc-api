<?php

namespace App\Repository;

use App\Entity\Repertorier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RepertorierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repertorier::class);
    }

    public function getNextNumeroSequentiel(): int
    {
        try {
            $max = $this->createQueryBuilder('r')
                ->select('MAX(r.numeroSequentiel)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            $max = null;
        }

        return ($max === null) ? 1 : ((int) $max + 1);
    }
}