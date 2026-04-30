<?php

namespace App\Service;

use App\Entity\Profil;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthService
{
    private ProfilRepository $repository;
    private EntityManagerInterface $em;
    private const JWT_SECRET = 'labolbc_gsb_secret_key_2026'; // Change en prod !

    public function __construct(
        ProfilRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * Login : vérifie username/password, retourne le Profil ou null
     */
    public function login(string $username, string $password): ?Profil
    {
        $profil = $this->repository->findOneBy(['username' => $username]);

        if (!$profil) {
            return null;
        }

        $hashedPassword = $profil->getPassword();

        // Vérifier mot de passe haché (bcrypt)
        if (password_verify($password, $hashedPassword)) {
            return $profil;
        }

        // Fallback : mot de passe en clair (migration legacy)
        if ($password === $hashedPassword) {
            $newHashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $profil->setPassword($newHashedPassword);
            $this->em->flush();
            return $profil;
        }

        return null;
    }

    /**
     * Génère un JWT token HS256
     */
    public function jwtGenerate(array $payload): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload['iat'] = time();
        $payload['exp'] = time() + 3600; // Expire dans 1h

        $headerB64 = $this->base64UrlEncode($header);
        $payloadB64 = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$headerB64.$payloadB64", self::JWT_SECRET, true);
        $signatureB64 = $this->base64UrlEncode($signature);

        return "$headerB64.$payloadB64.$signatureB64";
    }

    /**
     * Décode et vérifie un JWT token
     */
    public function jwtDecode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$headerB64, $payloadB64, $signatureB64] = $parts;

        // Vérifier signature
        $signature = hash_hmac('sha256', "$headerB64.$payloadB64", self::JWT_SECRET, true);
        if (!hash_equals($this->base64UrlEncode($signature), $signatureB64)) {
            return null; // Signature invalide
        }

        $payload = json_decode($this->base64UrlDecode($payloadB64), true);

        // Vérifier expiration
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            return null; // Token expiré
        }

        return $payload;
    }

    /**
     * Récupère l'utilisateur connecté depuis le token Bearer
     */
    public function getConnectedProfil(Request $request): ?Profil
    {
        $authHeader = $request->headers->get('Authorization', '');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }

        $token = substr($authHeader, 7);
        $payload = $this->jwtDecode($token);

        if (!$payload || !isset($payload['profil']['id'])) {
            return null;
        }

        return $this->repository->find($payload['profil']['id']);
    }

    /**
     * Vérifie si le profil est visiteur
     */
    public function isVisiteur(int $id): bool
    {
        $profil = $this->repository->find($id);
        return $profil && $profil->getUsertype() === 'visiteur';
    }

    /**
     * Vérifie si le profil est admin
     */
    public function isAdmin(int $id): bool
    {
        $profil = $this->repository->find($id);
        return $profil && $profil->getUsertype() === 'admin';
    }

    /**
     * Vérifie si le profil est responsable
     */
    public function isResponsable(int $id): bool
    {
        $profil = $this->repository->find($id);
        return $profil && $profil->getUsertype() === 'responsable';
    }

    // Helpers Base64URL (RFC 4648)
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}