<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\Profil;

#[Route('/api')]
class AuthController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // Géré par le firewall json_login
        return $this->json(['error' => 'Invalid credentials'], 401);
    }

    #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // JWT stateless : le client supprime le token
        return $this->json(['message' => 'Déconnexion réussie'], 200);
    }

    #[Route('/me', name: 'api_me', methods: ['GET'])]
    public function me(#[CurrentUser] ?Profil $profil): JsonResponse
    {
        if (!$profil) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        return $this->json([
            'id' => $profil->getId(),
            'username' => $profil->getUsername(),
            'type' => $profil->getUsertype(),
            'roles' => $profil->getRoles(),
        ]);
    }
}