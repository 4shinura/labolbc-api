<?php

namespace App\Controller\Api;

use App\Entity\Echantillon;
use App\Entity\Profil;
use App\Entity\Repertorier;
use App\Entity\Visite;
use App\Entity\Visiteur;
use App\Repository\MedicamentRepository;
use App\Repository\PraticienRepository;
use App\Repository\RepertorierRepository;
use App\Repository\VisiteRepository;
use App\Repository\VisiteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/visiteur')]
class VisiteurController extends AbstractController
{
    public function __construct(
        private VisiteRepository $visiteRepository,
        private VisiteurRepository $visiteurRepository,
        private PraticienRepository $praticienRepository,
        private MedicamentRepository $medicamentRepository,
        private RepertorierRepository $repertorierRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    private function getVisiteurFromProfil(Profil $profil): ?Visiteur
    {
        return $profil->getVisiteur();
    }

    /**
     * Voir ses visites - GET /api/visiteur/visites
     */
    #[Route('/visites', name: 'api_visiteur_visites', methods: ['GET'])]
    public function getMesVisites(#[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil || !$profil->getVisiteur()) {
            return $this->json(['error' => 'Non authentifié ou profil visiteur requis'], 401);
        }

        $visites = $this->visiteRepository->findByVisiteur($profil->getVisiteur());

        $json = $this->serializer->serialize($visites, 'json', ['groups' => 'visite:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Voir un de ses comptes-rendus - GET /api/visiteur/visites/{id}
     */
    #[Route('/visites/{id}', name: 'api_visiteur_visite_show', methods: ['GET'])]
    public function getMonCompteRendu(int $id, #[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil || !$profil->getVisiteur()) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visite = $this->visiteRepository->findOneByVisiteurAndId($profil->getVisiteur(), $id);

        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée ou non autorisée'], 404);
        }

        $json = $this->serializer->serialize($visite, 'json', ['groups' => 'visite:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * Ajouter une visite - POST /api/visiteur/visites
     * Reproduit la logique legacy : vérifie praticien non déjà dans portefeuille, médicaments uniques
     */
    #[Route('/visites', name: 'api_visiteur_visite_add', methods: ['POST'])]
    public function addVisite(Request $request, #[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil || !$profil->getVisiteur()) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $profil->getVisiteur();
        $data = json_decode($request->getContent(), true);

        // Validation des données requises
        if (empty($data['motifVisite']) || empty($data['dateVisite']) || empty($data['praticien'])) {
            return $this->json(['error' => 'motifVisite, dateVisite et praticien sont requis'], 400);
        }

        // Récupération et validation du praticien
        $praticienData = $data['praticien'];
        $numSeq = $praticienData['numSeq'] ?? null;
        $idPraticien = $praticienData['id'] ?? null;

        if (!$numSeq || !$idPraticien) {
            return $this->json(['error' => 'numSeq et id du praticien sont requis'], 400);
        }

        $praticien = $this->praticienRepository->findOneBy(['numSeq' => $numSeq, 'id' => $idPraticien]);
        if (!$praticien) {
            return $this->json(['error' => 'Praticien non trouvé'], 404);
        }

        // VÉRIFICATION PORTEFEUILLE : le praticien est-il déjà dans le portefeuille ?
        $existingRepertorier = $this->repertorierRepository->findOneBy([
            'visiteur' => $visiteur,
            'praticien' => $praticien
        ]);

        if ($existingRepertorier) {
            return $this->json([
                'error' => 'Visite non ajoutée',
                'message' => "Le praticien {$praticien->getNom()} {$praticien->getPrenom()} existe déjà dans votre portefeuille (vous avez déjà visité ce praticien)."
            ], 409);
        }

        // VÉRIFICATION MÉDICAMENTS UNIQUES
        $echantillonsData = $data['echantillons'] ?? [];
        if (!empty($echantillonsData)) {
            $medicamentIds = array_column($echantillonsData, 'idMedicament');
            if (count($medicamentIds) !== count(array_unique($medicamentIds))) {
                return $this->json([
                    'error' => 'Visite non ajoutée',
                    'message' => "Au moins un médicament a été spécifié plusieurs fois"
                ], 409);
            }
        }

        // Création de la visite
        $visite = new Visite();
        $visite->setVisiteur($visiteur);
        $visite->setPraticien($praticien);
        $visite->setDate(new \DateTime($data['dateVisite']));
        $visite->setMotif($data['motifVisite']);
        $visite->setBilan($data['bilanVisite'] ?? null);

        // Ajout des échantillons
        foreach ($echantillonsData as $echantillonData) {
            $medicament = $this->medicamentRepository->find($echantillonData['idMedicament']);
            if ($medicament) {
                $echantillon = new Echantillon();
                $echantillon->setMedicament($medicament);
                $echantillon->setQuantite($echantillonData['quantite'] ?? 1);
                $visite->addEchantillon($echantillon);
            }
        }

        $errors = $this->validator->validate($visite);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], 400);
        }

        // Transaction : ajouter au portefeuille + créer la visite
        $repertorier = new Repertorier();
        $repertorier->setVisiteur($visiteur);
        $repertorier->setPraticien($praticien);

        $this->em->persist($repertorier);
        $this->em->persist($visite);
        $this->em->flush();

        return $this->json([
            'message' => "Visite pour le motif : {$visite->getMotif()} du praticien {$praticien->getNom()} {$praticien->getPrenom()}, ajoutée avec succès !",
            'idVisite' => $visite->getId()
        ], 201);
    }

    /**
     * Créer un compte rendu - POST /api/visiteur/visites/{id}/report
     * Génère un PDF et l'associe à la visite
     */
    #[Route('/visites/{id}/report', name: 'api_visiteur_compte_rendu', methods: ['POST'])]
    public function createCompteRendu(int $id, Request $request, #[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil || !$profil->getVisiteur()) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visite = $this->visiteRepository->findOneByVisiteurAndId($profil->getVisiteur(), $id);

        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée ou non autorisée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Mise à jour du bilan et lien PDF si fournis
        if (isset($data['bilanVisite'])) {
            $visite->setBilan($data['bilanVisite']);
        }

        // Génération PDF si demandé ou si fichier uploadé
        if (isset($data['generatePdf']) && $data['generatePdf'] === true) {
            // Appel au service de génération PDF (voir ci-dessous)
            $pdfPath = $this->generatePdfForVisite($visite);
            $visite->setCompteRendu($pdfPath);
        } elseif (isset($data['lienPdf'])) {
            $visite->setCompteRendu($data['lienPdf']);
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Compte rendu créé avec succès',
            'pdf_url' => $visite->getCompteRendu()
        ], 200);
    }

    /**
     * Modifier une visite - PUT /api/visiteur/visites/{id}
     * Reproduit la logique legacy avec gestion du portefeuille
     */
    #[Route('/visites/{id}', name: 'api_visiteur_visite_update', methods: ['PUT'])]
    public function updateVisite(int $id, Request $request, #[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil || !$profil->getVisiteur()) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $profil->getVisiteur();
        $visite = $this->visiteRepository->findOneByVisiteurAndId($visiteur, $id);

        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $oldPraticien = $visite->getPraticien();

        // Si changement de praticien
        if (isset($data['praticien'])) {
            $praticienData = $data['praticien'];
            $numSeq = $praticienData['numSeq'] ?? null;
            $idPraticien = $praticienData['id'] ?? null;

            if ($numSeq && $idPraticien) {
                $newPraticien = $this->praticienRepository->findOneBy(['numSeq' => $numSeq, 'id' => $idPraticien]);

                if ($newPraticien && ($newPraticien->getNumSeq() !== $oldPraticien->getNumSeq() || $newPraticien->getId() !== $oldPraticien->getId())) {
                    // Supprimer l'ancien répertoire
                    $oldRepertorier = $this->repertorierRepository->findOneBy([
                        'visiteur' => $visiteur,
                        'praticien' => $oldPraticien
                    ]);
                    if ($oldRepertorier) {
                        $this->em->remove($oldRepertorier);
                    }

                    // Vérifier que le nouveau n'est pas déjà dans le portefeuille
                    $existingRepertorier = $this->repertorierRepository->findOneBy([
                        'visiteur' => $visiteur,
                        'praticien' => $newPraticien
                    ]);

                    if ($existingRepertorier) {
                        return $this->json([
                            'error' => 'Modification impossible',
                            'message' => "Le praticien {$newPraticien->getNom()} {$newPraticien->getPrenom()} existe déjà dans votre portefeuille."
                        ], 409);
                    }

                    // Ajouter le nouveau au portefeuille
                    $newRepertorier = new Repertorier();
                    $newRepertorier->setVisiteur($visiteur);
                    $newRepertorier->setPraticien($newPraticien);
                    $this->em->persist($newRepertorier);

                    $visite->setPraticien($newPraticien);
                }
            }
        }

        // Mise à jour des champs
        if (isset($data['dateVisite'])) {
            $visite->setDate(new \DateTime($data['dateVisite']));
        }
        if (isset($data['motifVisite'])) {
            $visite->setMotif($data['motifVisite']);
        }
        if (isset($data['bilanVisite'])) {
            $visite->setBilan($data['bilanVisite']);
        }
        if (isset($data['lienPdfVisite'])) {
            $visite->setCompteRendu($data['lienPdfVisite']);
        }

        // Mise à jour des échantillons
        if (isset($data['echantillons'])) {
            $medicamentIds = array_column($data['echantillons'], 'idMedicament');
            if (count($medicamentIds) !== count(array_unique($medicamentIds))) {
                return $this->json([
                    'error' => 'Modification impossible',
                    'message' => "Au moins un médicament a été spécifié plusieurs fois"
                ], 409);
            }

            $visite->clearEchantillons();
            foreach ($data['echantillons'] as $echantillonData) {
                $medicament = $this->medicamentRepository->find($echantillonData['idMedicament']);
                if ($medicament) {
                    $echantillon = new Echantillon();
                    $echantillon->setMedicament($medicament);
                    $echantillon->setQuantite($echantillonData['quantite'] ?? 1);
                    $visite->addEchantillon($echantillon);
                }
            }
        }

        // Régénération PDF si compte rendu existant
        if ($visite->getCompteRendu()) {
            $pdfPath = $this->generatePdfForVisite($visite);
            $visite->setCompteRendu($pdfPath);
        }

        $errors = $this->validator->validate($visite);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], 400);
        }

        $this->em->flush();

        return $this->json([
            'message' => "Visite pour le motif : {$visite->getMotif()} du praticien {$visite->getPraticien()->getNom()} {$visite->getPraticien()->getPrenom()}, modifiée avec succès"
        ], 200);
    }

    /**
     * Supprimer une visite - DELETE /api/visiteur/visites/{id}
     */
    #[Route('/visites/{id}', name: 'api_visiteur_visite_delete', methods: ['DELETE'])]
    public function deleteVisite(int $id, #[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil || !$profil->getVisiteur()) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $visiteur = $profil->getVisiteur();
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
            'message' => "Visite pour le motif : {$visite->getMotif()} du praticien {$visite->getPraticien()->getNom()} {$visite->getPraticien()->getPrenom()}, supprimée avec succès"
        ], 200);
    }

    /**
     * Service interne de génération PDF
     */
    private function generatePdfForVisite(Visite $visite): string
    {
        // Utiliser DomPDF ou TCPDF
        // Retourne le chemin relatif du fichier généré
        $filename = 'compte_rendu_' . $visite->getId() . '.pdf';
        $filepath = '/uploads/reports/' . $filename;
        
        // Logique de génération PDF ici...
        // $pdf = new \Dompdf\Dompdf();
        // $pdf->loadHtml(...);
        // $pdf->render();
        // file_put_contents($this->getParameter('kernel.project_dir') . '/public' . $filepath, $pdf->output());
        
        return $filepath;
    }
}