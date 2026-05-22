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
