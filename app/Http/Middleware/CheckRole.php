<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Manipule la requête entrante.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles Liste des rôles acceptés (ex: 'admin', 'chef_filiere')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Vérifier si un utilisateur est bien connecté
        if (! $user) {
            return response()->json([
                'message' => 'Non authentifié. Vous devez être connecté.',
            ], 401);
        }

        // 2. Vérifier si le rôle de l'utilisateur fait partie des rôles autorisés
        if (! in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Accès refusé : privilèges insuffisants pour cette action.',
            ], 403);
        }

        return $next($request);
    }
}
