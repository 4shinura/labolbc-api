<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class AuthController extends AbstractController
{
    public function __construct(private AuthService $authService) {}

    /**
     * Se connecter - POST /api/login
     * Body: {email, password}
     * Retourne: {token, profil: {id, username, type}}
     */
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        $profil = $this->authService->login($username, $password);

        if (!$profil) {
            return $this->json(['error' => 'Identifiants incorrects'], 401);
        }

        // Générer le JWT
        $token = $this->authService->jwtGenerate([
            'user' => [
                'id' => $profil->getId(),
                'username' => $profil->getUsername(),
                'type' => $profil->getUsertype()
            ]
        ]);

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $profil->getId(),
                'username' => $profil->getUsername(),
                'type' => $profil->getUsertype()
            ]
        ]);
    }

    /**
     * Se déconnecter - POST /api/logout
     * Le client supprime le token. On retourne juste un succès.
     */
    #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return $this->json(['message' => 'Déconnexion réussie'], 200);
    }

    /**
     * Récupérer le profil connecté - GET /api/me
     * Pour vérifier le token côté Android
     */
    #[Route('/me', name: 'api_me', methods: ['GET'])]
    public function me(Request $request): JsonResponse
    {
        $profil = $this->authService->getConnectedProfil($request);

        if (!$profil) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        return $this->json([
            'id' => $profil->getId(),
            'username' => $profil->getUsername(),
            'type' => $profil->getUsertype()
        ]);
    }
}