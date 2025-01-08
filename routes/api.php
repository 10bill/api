<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'admin'])->put('/admin/promote/{id}', [AdminController::class, 'promoteToSuperAdmin']);



Route::middleware(['auth:sanctum', 'admin'])->post('/admin/create', [AdminController::class, 'createAdmin']);

Route::post('/admin/register', [AdminController::class, 'registerAdmin']); // Inscription admin
Route::post('/admin/login', [AdminController::class, 'loginAdmin']); // Connexion admin

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'voir_user']); // Liste des utilisateurs
    Route::post('/admin/users', [AdminController::class, 'create_user']); // Créer un utilisateur
    Route::put('/admin/users/{id}', [AdminController::class, 'update_user']); // Modifier un utilisateur
    Route::delete('/admin/users/{id}', [AdminController::class, 'delete_user']); // Supprimer un utilisateur
});

// Routes pour utilisateurs connectés
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['user' => $request->user(), 'role' => $request->user()->role]);
    });
});

    // Déconnexion pour utilisateurs/admins
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);


// Routes publiques
Route::get('posts', [PostController::class, 'index']); // Voir tous les posts
Route::post('/register', [UserController::class, 'register']); // Inscription
Route::post('/login', [UserController::class, 'login']); // Connexion utilisateur



// Routes protégées pour utilisateurs connectés
Route::middleware('auth:sanctum')->group(function () {
    // Gestion des posts
    Route::post('posts/create', [PostController::class, 'store']); // Créer un post
    Route::put('posts/edit/{post}', [PostController::class, 'update']); // Modifier un post
    Route::delete('posts/{post}', [PostController::class, 'delete']); // Supprimer un post

    // Informations de l'utilisateur connecté
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
            'role' => $request->user()->role,
        ]);
    });
});