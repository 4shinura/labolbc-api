<?php

namespace App\Repository;

use App\Entity\Visite;
use App\Entity\Visiteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->andWhere('v.motifVisite LIKE :search')
            ->orWhere('v.bilanVisite LIKE :search')
            ->orWhere('p.nomPraticien LIKE :search')
            ->orWhere('p.prenomPraticien LIKE :search')
            ->orWhere('vi.nomVisiteur LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    public function findByVisiteur(Visiteur $visiteur): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.visiteur = :visiteur')
            ->setParameter('visiteur', $visiteur)
            ->orderBy('v.dateVisite', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByVisiteurAndId(Visiteur $visiteur, int $id): ?Visite
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.visiteur = :visiteur')
            ->andWhere('v.idVisite = :id')
            ->setParameter('visiteur', $visiteur)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}