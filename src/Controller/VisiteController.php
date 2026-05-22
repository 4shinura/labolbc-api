<?php

namespace App\Controller;

use App\Entity\Proposer;
use App\Entity\Visite;
use App\Entity\Visiteur;
use App\Entity\Medicament;
use App\Entity\Praticien;
use App\Repository\VisiteRepository;
use App\Repository\VisiteurRepository;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class VisiteController extends AbstractController
{
    public function __construct(
        private VisiteRepository $visiteRepository,
        private SerializerInterface $serializer,
        private AuthService $authService,
        private EntityManagerInterface $entityManager,
        private VisiteurRepository $visiteurRepository
    ) {}

    /**
     * Voir ses visites - GET /api/visiteur/visites
     */
    #[Route('/visiteur/visites', name: 'api_visiteur_visites_list', methods: ['GET'])]
    public function listVisiteur(Request $request): JsonResponse
    {
        $profil = $this->authService->getConnectedProfil($request);
        if (!$profil) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $this->visiteurRepository->findOneBy(['profil' => $profil]);
        if (!$visiteur) {
            return $this->json(['error' => 'Profil visiteur non trouvé'], 404);
        }

        $visites = $this->visiteRepository->findByVisiteur($visiteur);

        $json = $this->serializer->serialize($visites, 'json', ['groups' => 'visite:list']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Ajouter une visite - POST /api/visiteur/visites
     */
    #[Route('/visiteur/visites', name: 'api_visiteur_visites_add', methods: ['POST'])]
    public function addVisite(Request $request): JsonResponse
    {
        $profil = $this->authService->getConnectedProfil($request);
        if (!$profil) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $this->visiteurRepository->findOneBy(['profil' => $profil]);
        if (!$visiteur) {
            return $this->json(['error' => 'Profil visiteur non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Extraction Praticien (supporte le format imbriqué envoyé par le client)
        $idPraticien = $data['praticien']['idPraticien'] ?? $data['idPraticien'] ?? null;
        $numeroSequentiel = $data['praticien']['specialitePraticien']['numeroSequentiel'] ?? $data['numeroSequentiel'] ?? null;

        if (!$idPraticien || !$numeroSequentiel) {
            return $this->json(['error' => 'Les informations du praticien (idPraticien et numeroSequentiel) sont requises'], 400);
        }

        $praticien = $this->entityManager->getRepository(Praticien::class)->findOneBy([
            'idPraticien' => $idPraticien,
            'numeroSequentiel' => $numeroSequentiel
        ]);

        if (!$praticien) {
            return $this->json(['error' => 'Praticien non trouvé'], 404);
        }

        $visite = new Visite();
        $visite->setVisiteur($visiteur);
        $visite->setDateVisite(new \DateTime($data['dateVisite'] ?? 'now'));
        $visite->setMotifVisite($data['motifVisite'] ?? null);
        $visite->setBilanVisite($data['bilanVisite'] ?? null);
        $visite->setPraticien($praticien);
        $visite->setIdPraticien($praticien->getIdPraticien());
        $visite->setNumeroSequentiel($praticien->getNumeroSequentiel());
        $visite->setIdVisiteur($visiteur->getIdVisiteur());

        $this->entityManager->persist($visite);
        $this->entityManager->flush(); // On flush pour avoir l'idVisite

        // Propositions
        if (isset($data['propositions']) && is_array($data['propositions'])) {
            foreach ($data['propositions'] as $propData) {
                $medicament = $this->entityManager->getRepository(Medicament::class)->find($propData['idMedicament']);
                if ($medicament) {
                    $proposer = new Proposer();
                    $proposer->setVisite($visite);
                    $proposer->setMedicament($medicament);
                    // Supporte plusieurs formats de nommage pour nb_echantillons
                    $nb = $propData['nb_echantillon'] ?? $propData['nb_echantillons'] ?? $propData['nbEchantillons'] ?? 0;
                    $proposer->setNbEchantillon($nb);
                    $proposer->setIdVisite($visite->getIdVisite());
                    $proposer->setIdMedicament($medicament->getIdMedicament());
                    $this->entityManager->persist($proposer);
                }
            }
            $this->entityManager->flush();
        }

        $json = $this->serializer->serialize($visite, 'json', ['groups' => 'visite:read']);
        return new JsonResponse($json, 201, [], true);
    }

    /**
     * Modifier une visite - PUT /api/visiteur/visites/{id}
     */
    #[Route('/visiteur/visites/{id}', name: 'api_visiteur_visites_update', methods: ['PUT'])]
    public function updateVisite(int $id, Request $request): JsonResponse
    {
        $profil = $this->authService->getConnectedProfil($request);
        if (!$profil) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $this->visiteurRepository->findOneBy(['profil' => $profil]);
        if (!$visiteur) {
            return $this->json(['error' => 'Profil visiteur non trouvé'], 404);
        }

        $visite = $this->visiteRepository->findOneByVisiteurAndId($visiteur, $id);
        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['dateVisite'])) $visite->setDateVisite(new \DateTime($data['dateVisite']));
        if (isset($data['motifVisite'])) $visite->setMotifVisite($data['motifVisite']);
        if (isset($data['bilanVisite'])) $visite->setBilanVisite($data['bilanVisite']);

        // Praticien (supporte le format imbriqué envoyé par le client)
        $idPraticien = $data['praticien']['idPraticien'] ?? $data['idPraticien'] ?? null;
        $numeroSequentiel = $data['praticien']['specialitePraticien']['numeroSequentiel'] ?? $data['numeroSequentiel'] ?? null;

        if ($idPraticien && $numeroSequentiel) {
            $praticien = $this->entityManager->getRepository(Praticien::class)->findOneBy([
                'idPraticien' => $idPraticien,
                'numeroSequentiel' => $numeroSequentiel
            ]);
            if ($praticien) {
                $visite->setPraticien($praticien);
                $visite->setIdPraticien($praticien->getIdPraticien());
                $visite->setNumeroSequentiel($praticien->getNumeroSequentiel());
            }
        }

        // Propositions
        if (isset($data['propositions']) && is_array($data['propositions'])) {
            // Supprimer les anciennes propositions
            $visite->clearPropositions();
            $this->entityManager->flush();

            foreach ($data['propositions'] as $propData) {
                $medicament = $this->entityManager->getRepository(Medicament::class)->find($propData['idMedicament']);
                if ($medicament) {
                    $proposer = new Proposer();
                    $proposer->setVisite($visite);
                    $proposer->setMedicament($medicament);
                    // Supporte plusieurs formats de nommage pour nb_echantillons
                    $nb = $propData['nb_echantillon'] ?? $propData['nb_echantillons'] ?? $propData['nbEchantillons'] ?? 0;
                    $proposer->setNbEchantillon($nb);
                    $proposer->setIdVisite($visite->getIdVisite());
                    $proposer->setIdMedicament($medicament->getIdMedicament());
                    $this->entityManager->persist($proposer);
                }
            }
        }

        $this->entityManager->flush();

        $json = $this->serializer->serialize($visite, 'json', ['groups' => 'visite:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Voir les visites (global search) - GET /api/visites?search=
     */
    #[Route('/visites', name: 'api_visites_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $search = $request->query->get('search');

        if ($search) {
            $visites = $this->visiteRepository->searchVisites($search);
        } else {
            $visites = $this->visiteRepository->findAll();
        }

        $json = $this->serializer->serialize($visites, 'json', ['groups' => 'visite:list']);
        return new JsonResponse($json, 200, [], true);
    }
}
