<?php

namespace App\Controller;

use App\Entity\Proposer;
use App\Entity\Repertorier;
use App\Entity\Visite;
use App\Entity\Medicament;
use App\Entity\Praticien;
use App\Repository\MedicamentRepository;
use App\Repository\PraticienRepository;
use App\Repository\PresenterRepository;
use App\Repository\RepertorierRepository;
use App\Repository\VisiteRepository;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/visiteur')]
class VisiteurController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private VisiteRepository $visiteRepository,
        private PraticienRepository $praticienRepository,
        private MedicamentRepository $medicamentRepository,
        private RepertorierRepository $repertorierRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    private function getVisiteurFromRequest(Request $request): ?\App\Entity\Visiteur
    {
        $profil = $this->authService->getConnectedProfil($request);
        if (!$profil || $profil->getTypeProfil() !== 'visiteur') {
            return null;
        }
        return $profil->getVisiteur();
    }

    /**
     * Voir ses visites - GET /api/visiteur/visites
     */
    #[Route('/visites', name: 'api_visiteur_visites', methods: ['GET'])]
    public function getMesVisites(Request $request): JsonResponse
    {
        $visiteur = $this->getVisiteurFromRequest($request);
        if (!$visiteur) {
            return $this->json(['error' => 'Non authentifié ou profil visiteur requis'], 401);
        }

        $visites = $this->visiteRepository->findByVisiteur($visiteur);
        $json = $this->serializer->serialize($visites, 'json', ['groups' => 'visite:list']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Ajouter une visite - POST /api/visiteur/visites
     */
    #[Route('/visites', name: 'api_visiteur_visite_add', methods: ['POST'])]
    public function addVisite(Request $request): JsonResponse
    {
        $visiteur = $this->getVisiteurFromRequest($request);
        if (!$visiteur) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $data = json_decode($request->getContent(), true);

        // Extraction Praticien (supporte le format imbriqué envoyé par le client)
        $idPraticien = $data['praticien']['idPraticien'] ?? $data['idPraticien'] ?? null;
        $numeroSequentiel = $data['praticien']['specialitePraticien']['numeroSequentiel'] ?? $data['numeroSequentiel'] ?? null;

        if (!$idPraticien || !$numeroSequentiel) {
            return $this->json(['error' => 'Les informations du praticien (idPraticien et numeroSequentiel) sont requises'], 400);
        }

        $praticien = $this->praticienRepository->findOneBy([
            'idPraticien' => $idPraticien,
            'numeroSequentiel' => $numeroSequentiel
        ]);

        if (!$praticien) {
            return $this->json(['error' => 'Praticien non trouvé'], 404);
        }

        // Vérification portefeuille
        $existingRepertorier = $this->repertorierRepository->findOneBy([
            'visiteur' => $visiteur,
            'praticien' => $praticien
        ]);

        if ($existingRepertorier) {
            return $this->json([
                'error' => 'Visite non ajoutée',
                'message' => "Le praticien {$praticien->getNomPraticien()} {$praticien->getPrenomPraticien()} existe déjà dans votre portefeuille."
            ], 409);
        }

        // Création visite
        $visite = new Visite();
        $visite->setVisiteur($visiteur);
        $visite->setPraticien($praticien);
        $visite->setDateVisite(new \DateTime($data['dateVisite'] ?? 'now'));
        $visite->setMotifVisite($data['motifVisite'] ?? null);
        $visite->setBilanVisite($data['bilanVisite'] ?? null);
        
        // Champs techniques requis par le modèle de données legacy
        $visite->setIdVisiteur($visiteur->getIdVisiteur());
        $visite->setIdPraticien($praticien->getIdPraticien());
        $visite->setNumeroSequentiel($praticien->getNumeroSequentiel());

        $this->em->persist($visite);
        $this->em->flush(); // Flush pour avoir l'idVisite pour les propositions

        // Ajout des propositions
        $propositionsData = $data['propositions'] ?? $data['echantillons'] ?? [];
        foreach ($propositionsData as $propData) {
            $medicament = $this->medicamentRepository->find($propData['idMedicament']);
            if ($medicament) {
                $proposition = new Proposer();
                $proposition->setVisite($visite);
                $proposition->setMedicament($medicament);
                
                $nb = $propData['nb_echantillon'] ?? $propData['nb_echantillons'] ?? $propData['nbEchantillons'] ?? $propData['quantite'] ?? 0;
                $proposition->setNbEchantillon($nb);
                
                $proposition->setIdVisite($visite->getIdVisite());
                $proposition->setIdMedicament($medicament->getIdMedicament());
                
                $this->em->persist($proposition);
            }
        }

        // Création de l'entrée dans le portefeuille
        $repertorier = new Repertorier();
        $repertorier->setVisiteur($visiteur);
        $repertorier->setPraticien($praticien);
        $repertorier->setIdVisiteur($visiteur->getIdVisiteur());
        $repertorier->setIdPraticien($praticien->getIdPraticien());
        $repertorier->setNumeroSequentiel($this->repertorierRepository->getNextNumeroSequentiel());

        $this->em->persist($repertorier);
        $this->em->flush();

        return $this->json([
            'message' => "Visite pour le motif : {$visite->getMotifVisite()} du praticien {$praticien->getNomPraticien()} {$praticien->getPrenomPraticien()}, ajoutée avec succès !",
            'idVisite' => $visite->getIdVisite()
        ], 201);
    }

    /**
     * Modifier une visite - PUT /api/visiteur/visites/{id}
     */
    #[Route('/visites/{id}', name: 'api_visiteur_visite_update', methods: ['PUT'])]
    public function updateVisite(int $id, Request $request): JsonResponse
    {
        $visiteur = $this->getVisiteurFromRequest($request);
        if (!$visiteur) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visite = $this->visiteRepository->findOneByVisiteurAndId($visiteur, $id);
        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $oldPraticien = $visite->getPraticien();

        // Extraction Praticien (supporte le format imbriqué envoyé par le client)
        $idPraticien = $data['praticien']['idPraticien'] ?? $data['idPraticien'] ?? null;
        $numeroSequentiel = $data['praticien']['specialitePraticien']['numeroSequentiel'] ?? $data['numeroSequentiel'] ?? null;

        // Changement de praticien
        if ($idPraticien && $numeroSequentiel) {
            $newPraticien = $this->praticienRepository->findOneBy([
                'idPraticien' => $idPraticien,
                'numeroSequentiel' => $numeroSequentiel
            ]);

            if ($newPraticien && ($newPraticien->getIdPraticien() !== $oldPraticien->getIdPraticien() || $newPraticien->getNumeroSequentiel() !== $oldPraticien->getNumeroSequentiel())) {
                // Supprimer ancien répertoire
                $oldRepertorier = $this->repertorierRepository->findOneBy([
                    'visiteur' => $visiteur,
                    'praticien' => $oldPraticien
                ]);
                if ($oldRepertorier) {
                    $this->em->remove($oldRepertorier);
                }

                // Vérifier nouveau pas déjà dans portefeuille
                $existingRepertorier = $this->repertorierRepository->findOneBy([
                    'visiteur' => $visiteur,
                    'praticien' => $newPraticien
                ]);

                if ($existingRepertorier) {
                    return $this->json([
                        'error' => 'Modification impossible',
                        'message' => "Le praticien {$newPraticien->getNomPraticien()} {$newPraticien->getPrenomPraticien()} existe déjà dans votre portefeuille."
                    ], 409);
                }

                $newRepertorier = new Repertorier();
                $newRepertorier->setVisiteur($visiteur);
                $newRepertorier->setPraticien($newPraticien);
                $newRepertorier->setIdVisiteur($visiteur->getIdVisiteur());
                $newRepertorier->setIdPraticien($newPraticien->getIdPraticien());
                $newRepertorier->setNumeroSequentiel($this->repertorierRepository->getNextNumeroSequentiel());
                $this->em->persist($newRepertorier);

                $visite->setPraticien($newPraticien);
                $visite->setIdPraticien($newPraticien->getIdPraticien());
                $visite->setNumeroSequentiel($newPraticien->getNumeroSequentiel());
            }
        }

        // Mise à jour champs
        if (isset($data['dateVisite'])) $visite->setDateVisite(new \DateTime($data['dateVisite']));
        if (isset($data['motifVisite'])) $visite->setMotifVisite($data['motifVisite']);
        if (isset($data['bilanVisite'])) $visite->setBilanVisite($data['bilanVisite']);
        if (isset($data['compteRenduVisite'])) $visite->setCompteRenduVisite($data['compteRenduVisite']);

        // Mise à jour propositions
        if (isset($data['propositions']) || isset($data['echantillons'])) {
            $propositionsData = $data['propositions'] ?? $data['echantillons'] ?? [];
            
            $visite->clearPropositions();
            $this->em->flush();
            
            foreach ($propositionsData as $propData) {
                $medicament = $this->medicamentRepository->find($propData['idMedicament']);
                if ($medicament) {
                    $proposition = new Proposer();
                    $proposition->setVisite($visite);
                    $proposition->setMedicament($medicament);
                    
                    $nb = $propData['nb_echantillon'] ?? $propData['nb_echantillons'] ?? $propData['nbEchantillons'] ?? $propData['quantite'] ?? 0;
                    $proposition->setNbEchantillon($nb);
                    
                    $proposition->setIdVisite($visite->getIdVisite());
                    $proposition->setIdMedicament($medicament->getIdMedicament());
                    
                    $this->em->persist($proposition);
                }
            }
        }

        $this->em->flush();

        return $this->json([
            'message' => "Visite pour le motif : {$visite->getMotifVisite()} du praticien {$visite->getPraticien()->getNomPraticien()} {$visite->getPraticien()->getPrenomPraticien()}, modifiée avec succès"
        ]);
    }

    /**
     * Supprimer une visite - DELETE /api/visiteur/visites/{id}
     */
    #[Route('/visites/{id}', name: 'api_visiteur_visite_delete', methods: ['DELETE'])]
    public function deleteVisite(int $id, Request $request): JsonResponse
    {
        $visiteur = $this->getVisiteurFromRequest($request);
        if (!$visiteur) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visite = $this->visiteRepository->findOneByVisiteurAndId($visiteur, $id);
        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée'], 404);
        }

        // Supprimer du portefeuille
        $repertorier = $this->repertorierRepository->findOneBy([
            'visiteur' => $visiteur,
            'praticien' => $visite->getPraticien()
        ]);
        if ($repertorier) {
            $this->em->remove($repertorier);
        }

        $this->em->remove($visite);
        $this->em->flush();

        return $this->json([
            'message' => "Visite pour le motif : {$visite->getMotifVisite()} du praticien {$visite->getPraticien()->getNomPraticien()} {$visite->getPraticien()->getPrenomPraticien()}, supprimée avec succès"
        ]);
    }

    /**
     * Récupérer les praticiens de la même région que le visiteur connecté
     * GET /api/visiteur/praticiens
     */
    #[Route('/praticiens', name: 'api_visiteur_praticiens', methods: ['GET'])]
    public function getPraticiensByRegion(
        Request $request,
        PresenterRepository $presenterRepository,
        PraticienRepository $praticienRepository
    ): JsonResponse {
        $profil = $this->authService->getConnectedProfil($request);
        if (!$profil) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $profil->getVisiteur();
        if (!$visiteur) {
            return $this->json(['error' => 'Aucun visiteur associé à ce profil'], 404);
        }

        $presenters = $presenterRepository->findBy(['visiteur' => $visiteur]);
        if (empty($presenters)) {
            return $this->json(['praticiens' => []]);
        }

        $regionIds = array_map(fn($p) => $p->getRegion()->getNumRegion(), $presenters);
        $regionIds = array_unique($regionIds);

        $qb = $praticienRepository->createQueryBuilder('p')
            ->innerJoin('p.travails', 't')
            ->where('t.region IN (:regionIds)')
            ->setParameter('regionIds', $regionIds)
            ->distinct();

        $praticiens = $qb->getQuery()->getResult();

        $data = [];
        foreach ($praticiens as $praticien) {
            $specialite = $praticien->getSpecialite();
            $data[] = [
                'specialite' => $specialite ? [
                    'numeroSequentiel' => $specialite->getNumeroSequentiel(),
                    'libelle' => $specialite->getLibelle(),
                ] : null,
                'idPraticien' => $praticien->getIdPraticien(),
                'nom' => $praticien->getNomPraticien(),
                'prenom' => $praticien->getPrenomPraticien()
            ];
        }

        return $this->json(['praticiens' => $data]);
    }

    #[Route('/visites/{id}/pdf', name: 'api_visite_pdf', methods: ['GET'])]
    public function generatePdf(int $id, VisiteRepository $visiteRepository, Request $request): Response  
    {
        $visite = $visiteRepository->find($id);
        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée'], 404);
        }

        $logoUrl = $request->getSchemeAndHttpHost() . '/img/lab-logo-bgless.png';

        $data = [
            'visite' => $visite,
            'motifVisite' => $visite->getMotifVisite(),
            'dateVisite' => $visite->getDateVisite()->format('d/m/Y'),
            'visiteur' => $visite->getVisiteur()?->getNomVisiteur() . ' ' . $visite->getVisiteur()?->getNomVisiteur(),
            'praticien' => $visite->getPraticien()?->getNomPraticien() . ' ' . $visite->getPraticien()?->getPrenomPraticien(),
            'echantillons' => $this->formatEchantillons($visite),
            'bilan' => $visite->getBilanVisite() ?: 'Aucun bilan renseigné',
            'logo_url' => $logoUrl, 
        ];

        $html = $this->renderView('pdf/compte_rendu.html.twig', $data);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);  
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="compte_rendu_' . $visite->getIdVisite() . '.pdf"'
        ]);
    }

    private function formatEchantillons(Visite $visite): array
    {
        $echantillons = [];
        foreach ($visite->getPropositions() as $proposition) {
            $medicament = $proposition->getMedicament();
            $quantite = $proposition->getNbEchantillons();
            if ($medicament) {
                $echantillons[] = $quantite . ' échantillon(s) de ' . $medicament->getLibelle();
            }
        }
        return $echantillons;
    }
}