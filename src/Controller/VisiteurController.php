<?php

namespace App\Controller;

use App\Entity\Proposer;  // Remplace Echantillon
use App\Entity\Repertorier;
use App\Entity\Visite;
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
        $json = $this->serializer->serialize($visites, 'json', ['groups' => 'visite:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Voir un de ses comptes-rendus - GET /api/visiteur/visites/{id}
     */
    // #[Route('/visites/{id}', name: 'api_visiteur_visite_show', methods: ['GET'])]
    // public function getMonCompteRendu(int $id, Request $request): JsonResponse
    // {
    //     $visiteur = $this->getVisiteurFromRequest($request);
    //     if (!$visiteur) {
    //         return $this->json(['error' => 'Non authentifié'], 401);
    //     }

    //     $visite = $this->visiteRepository->findOneByVisiteurAndId($visiteur, $id);
    //     if (!$visite) {
    //         return $this->json(['error' => 'Visite non trouvée ou non autorisée'], 404);
    //     }

    //     $json = $this->serializer->serialize($visite, 'json', ['groups' => 'visite:read']);
    //     return new JsonResponse($json, 200, [], true);
    // }

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

        if (empty($data['motifVisite']) || empty($data['dateVisite']) || empty($data['praticien'])) {
            return $this->json(['error' => 'motifVisite, dateVisite et praticien sont requis'], 400);
        }

        $praticienData = $data['praticien'];
        $specialite = $praticienData['specialite'] ?? null;
        $idPraticien = $praticienData['idPraticien'] ?? null;

        if (!$specialite || !$idPraticien) {
            return $this->json(['error' => 'specialite et id du praticien sont requis'], 400);
        }

        $praticien = $this->praticienRepository->findOneBy(['specialite' => $specialite, 'idPraticien' => $idPraticien]);
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

        // Vérification médicaments uniques
        $propositionsData = $data['propositions'] ?? $data['echantillons'] ?? [];
        if (!empty($propositionsData)) {
            $medicamentIds = array_column($propositionsData, 'idMedicament');
            if (count($medicamentIds) !== count(array_unique($medicamentIds))) {
                return $this->json([
                    'error' => 'Visite non ajoutée',
                    'message' => "Au moins un médicament a été spécifié plusieurs fois"
                ], 409);
            }
        }

        // Création visite
        $visite = new Visite();
        $visite->setVisiteur($visiteur);
        $visite->setPraticien($praticien);
        $visite->setDateVisite(new \DateTime($data['dateVisite']));
        $visite->setMotifVisite($data['motifVisite']);
        $visite->setBilanVisite($data['bilanVisite'] ?? null);

        // Ajout des propositions (échantillons) - CORRIGÉ ICI
        foreach ($propositionsData as $propData) {
            $medicament = $this->medicamentRepository->find($propData['idMedicament']);
            if ($medicament) {
                $proposition = new Proposer();
                $proposition->setMedicament($medicament);
                $proposition->setNbEchantillon($propData['quantite'] ?? 1);
                $visite->addProposition($proposition);
            }
        }

        $errors = $this->validator->validate($visite);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], 400);
        }

        // Transaction
        $repertorier = new Repertorier();
        $repertorier->setVisiteur($visiteur);
        $repertorier->setPraticien($praticien);
        $repertorier->setIdVisiteur($visiteur->getIdVisiteur());
        $repertorier->setIdPraticien($praticien->getIdPraticien());
        $repertorier->setNumeroSequentiel($this->repertorierRepository->getNextNumeroSequentiel());

        $this->em->persist($repertorier);
        $this->em->persist($visite);
        $this->em->flush();

        return $this->json([
            'message' => "Visite pour le motif : {$visite->getMotifVisite()} du praticien {$praticien->getNomPraticien()} {$praticien->getPrenomPraticien()}, ajoutée avec succès !",
            'idVisite' => $visite->getIdVisite()
        ], 201);
    }

    /**
     * Créer un compte rendu - POST /api/visiteur/visites/{id}/report
     */
    #[Route('/visites/{id}/report', name: 'api_visiteur_compte_rendu', methods: ['POST'])]
    public function createCompteRendu(int $id, Request $request): JsonResponse
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

        if (isset($data['bilanVisite'])) {
            $visite->setBilanVisite($data['bilanVisite']);
        }

        if (isset($data['compteRenduVisite'])) {
            $visite->setCompteRenduVisite($data['compteRenduVisite']);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Compte rendu créé avec succès',
            'pdf_url' => $visite->getCompteRenduVisite()
        ]);
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

        // Changement de praticien
        if (isset($data['praticien'])) {
            $praticienData = $data['praticien'];
            $specialite = $praticienData['specialitePraticien'] ?? null;
            $idPraticien = $praticienData['idPraticien'] ?? null;

            if ($specialite && $idPraticien) {
                $newPraticien = $this->praticienRepository->findOneBy(['specialite' => $specialite, 'idPraticien' => $idPraticien]);

                if ($newPraticien && ($newPraticien->getSpecialitePraticien() !== $oldPraticien->getSpecialitePraticien() || $newPraticien->getIdPraticien() !== $oldPraticien->getIdPraticien())) {
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
                }
            }
        }

        // Mise à jour champs
        if (isset($data['dateVisite'])) {
            $visite->setDateVisite(new \DateTime($data['dateVisite']));
        }
        if (isset($data['motifVisite'])) {
            $visite->setMotifVisite($data['motifVisite']);
        }
        if (isset($data['bilanVisite'])) {
            $visite->setBilanVisite($data['bilanVisite']);
        }
        if (isset($data['compteRenduVisite'])) {
            $visite->setCompteRenduVisite($data['compteRenduVisite']);
        }

        // Mise à jour propositions (échantillons) - CORRIGÉ ICI
        if (isset($data['propositions']) || isset($data['echantillons'])) {
            $propositionsData = $data['propositions'] ?? $data['echantillons'] ?? [];
            
            $medicamentIds = array_column($propositionsData, 'idMedicament');
            if (count($medicamentIds) !== count(array_unique($medicamentIds))) {
                return $this->json([
                    'error' => 'Modification impossible',
                    'message' => "Au moins un médicament a été spécifié plusieurs fois"
                ], 409);
            }

            $visite->clearPropositions();  // CORRIGÉ ICI
            
            foreach ($propositionsData as $propData) {
                $medicament = $this->medicamentRepository->find($propData['idMedicament']);
                if ($medicament) {
                    $proposition = new Proposer();
                    $proposition->setMedicament($medicament);
                    $proposition->setNbEchantillon($propData['quantite'] ?? 1);
                    $visite->addProposition($proposition);  // CORRIGÉ ICI
                }
            }
        }

        $errors = $this->validator->validate($visite);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], 400);
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

        // Récupérer les régions du visiteur via Presenter
        $presenters = $presenterRepository->findBy(['visiteur' => $visiteur]);
        if (empty($presenters)) {
            return $this->json(['praticiens' => []]);
        }

        $regionIds = array_map(fn($p) => $p->getRegion()->getNumRegion(), $presenters);
        $regionIds = array_unique($regionIds);

        // Récupérer les praticiens qui travaillent dans ces régions via PraticienRepository
        $qb = $praticienRepository->createQueryBuilder('p')
            ->innerJoin('p.travails', 't')
            ->where('t.region IN (:regionIds)')
            ->setParameter('regionIds', $regionIds)
            ->distinct();

        $praticiens = $qb->getQuery()->getResult();

        $data = [];
        foreach ($praticiens as $praticien) {
            $specialite = $praticien->getSpecialitePraticien();
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

        // Générer le HTML à partir du template Twig
        $html = $this->renderView('pdf/compte_rendu.html.twig', $data);

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);  
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Retourner le PDF en réponse
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
            $quantite = $proposition->getNbEchantillon();
            if ($medicament) {
                $echantillons[] = $quantite . ' échantillon(s) de ' . $medicament->getLibelle();
            }
        }
        return $echantillons;
    }
}