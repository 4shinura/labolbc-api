<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Visiteur;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class AuthController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

   /**
     * S'inscrire - POST /api/register
     * Body: {name, email, password, usertype? (optionnel)}
     * Retourne: {token, profil: {id, name, email, type}}
     */
    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $requiredFields = ['name', 'email', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json([
                    'error' => "Le champ '$field' est requis"
                ], 400);
            }
        }

        $existingProfil = $this->entityManager
            ->getRepository(Profil::class)
            ->findOneBy(['email' => $data['email']]);
            
        if ($existingProfil) {
            return $this->json([
                'error' => 'Cet email est déjà utilisé'
            ], 409);
        }
        
        $profil = new Profil();
        $profil->setEmail($data['email']);
        $profil->setUsertype($data['device'] ?? 'visiteur');
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $profil->setPassword($hashedPassword);
        
        $errors = $this->validator->validate($profil);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        
        $this->entityManager->persist($profil);
        $this->entityManager->flush();
        
        $visiteur = new Visiteur();
        $visiteur->setNom($data['name']);
        $visiteur->setProfil($profil); 
        
        $errorsVisiteur = $this->validator->validate($visiteur);
        if (count($errorsVisiteur) > 0) {
            $errorMessages = [];
            foreach ($errorsVisiteur as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        
        $this->entityManager->persist($visiteur);
        $this->entityManager->flush();
        
        $token = $this->authService->jwtGenerate([
            'user' => [
                'id' => $profil->getId(),
                'name' => $visiteur->getNom(),
                'email' => $profil->getEmail(),
                'type' => $profil->getUsertype()
            ]
        ]);
        
        return $this->json([
            'message' => 'Inscription réussie',
            'token' => $token,
            'profil' => [
                'id' => $profil->getId(),
                'name' => $visiteur->getNom(),
                'email' => $profil->getEmail(),
                'type' => $profil->getUsertype()
            ]
        ], 201);
    }

    /**
     * Se connecter - POST /api/login
     * Body: {email, password}
     * Retourne: {token, profil: {id, email, type}}
     */
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $deviceType = $data['device'] ?? ''; 
        
        if (empty($deviceType)) {
            return $this->json(['error' => 'Le champ device est requis'], 400);
        }
        
        $profil = $this->authService->login($email, $password);

        if (!$profil) {
            return $this->json(['error' => 'Identifiants incorrects'], 401);
        }

        if ($profil->getUsertype() !== $deviceType) {
            return $this->json([
                'error' => 'Accès non autorisé. Ce compte n\'est pas compatible avec cet appareil.'
            ], 403);
        }

        $visiteur = $this->entityManager
            ->getRepository(Visiteur::class)
            ->findOneBy(['profil' => $profil->getId()]);

        // Générer le JWT
        $token = $this->authService->jwtGenerate([
            'user' => [
                'id' => $profil->getId(),
                'name' => $visiteur->getNom(),
                'email' => $profil->getEmail(),
                'type' => $profil->getUsertype()
            ]
        ]);

        return $this->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'profil' => [
                'id' => $profil->getId(),
                'name' => $visiteur->getNom(),
                'email' => $profil->getEmail(),
                'type' => $profil->getUsertype()
            ]
        ]);
    }

    /**
     * Se déconnecter - POST /api/logout
     * Le client supprime le token. On retourne juste un succès.
     */
    // #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    // public function logout(): JsonResponse
    // {
    //     return $this->json(['message' => 'Déconnexion réussie'], 200);
    // }

    // /**
    //  * Récupérer le profil connecté - GET /api/me
    //  * Pour vérifier le token côté Android
    //  */
    // #[Route('/me', name: 'api_me', methods: ['GET'])]
    // public function me(Request $request): JsonResponse
    // {
    //     $profil = $this->authService->getConnectedProfil($request);

    //     if (!$profil) {
    //         return $this->json(['error' => 'Non authentifié'], 401);
    //     }

    //     return $this->json([
    //         'id' => $profil->getId(),
    //         'email' => $profil->getEmail(),
    //         'type' => $profil->getUsertype()
    //     ]);
    // }
}