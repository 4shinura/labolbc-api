<?php

namespace App\Repository;

use App\Entity\Visite;
use App\Entity\Visiteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visite>
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    public function searchVisites(string $search): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.praticien', 'p')
            ->leftJoin('v.visiteur', 'vi')
            ->andWhere('v.motif LIKE :search')
            ->orWhere('v.bilan LIKE :search')
            ->orWhere('p.nom LIKE :search')
            ->orWhere('p.prenom LIKE :search')
            ->orWhere('vi.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    public function findByVisiteur(Visiteur $visiteur): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.visiteur = :visiteur')
            ->setParameter('visiteur', $visiteur)
            ->orderBy('v.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByVisiteurAndId(Visiteur $visiteur, int $id): ?Visite
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.visiteur = :visiteur')
            ->andWhere('v.id = :id')
            ->setParameter('visiteur', $visiteur)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}