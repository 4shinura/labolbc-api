<?php

namespace App\Controller;

use App\Repository\VisiteRepository;
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
        private SerializerInterface $serializer
    ) {}

    /**
     * Voir les visites - GET /api/visites?search=
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

    /**
     * Voir un compte rendu - GET /api/visites/{id}
     */
    #[Route('/visites/{id}', name: 'api_visite_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $visite = $this->visiteRepository->find($id);

        if (!$visite) {
            return $this->json(['error' => 'Visite non trouvée'], 404);
        }

        if ($visite->getCompteRendu()) {
            return $this->json([
                'visite' => json_decode($this->serializer->serialize($visite, 'json', ['groups' => 'visite:read']), true),
                'pdf_url' => $visite->getCompteRendu(),
                'has_pdf' => true
            ]);
        }

        $json = $this->serializer->serialize($visite, 'json', ['groups' => 'visite:read']);
        return new JsonResponse($json, 200, [], true);
    }
}