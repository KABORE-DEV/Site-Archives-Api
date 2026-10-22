<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Connexion de l'utilisateur et génération du token Sanctum.
     * Endpoint : POST /api/login
     */
    public function login(Request $request): JsonResponse
    {
        // 1. Validation des champs reçus d'Angular
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Recherche de l'utilisateur par son email
        $user = User::where('email', $request->email)->first();

        // 3. Vérification du mot de passe
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants incorrects. Veuillez vérifier votre adresse e-mail et votre mot de passe.',
            ], 401);
        }

        // 4. Création du Token d'accès API (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Réponse JSON complète renvoyée à Angular
        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'filiere_id' => $user->filiere_id,
                'filiere' => $user->filiere ? [
                    'id' => $user->filiere->id,
                    'nom' => $user->filiere->nom,
                    'code' => $user->filiere->code,
                ] : null,
            ],
        ], 200);
    }

    /**
     * Récupère le profil de l'utilisateur connecté actuellement.
     * Endpoint : GET /api/me (Nécessite le token)
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('filiere');

        return response()->json([
            'user' => $user,
        ], 200);
    }

    /**
     * Déconnexion (révocation du token actuel).
     * Endpoint : POST /api/logout (Nécessite le token)
     */
    public function logout(Request $request): JsonResponse
    {
        // Supprime le token qui a servi à cette requête
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie. Le jeton d’accès a été révoqué.',
        ], 200);
    }
}
