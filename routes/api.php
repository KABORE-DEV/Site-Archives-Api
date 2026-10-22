<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques (sans authentification)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Routes Protégées (Nécessitent un Bearer Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Profil de l'utilisateur connecté
    Route::get('/me', [AuthController::class, 'me']);
    
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
});
