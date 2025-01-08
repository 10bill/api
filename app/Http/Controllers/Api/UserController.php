<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogUserRequest;
use App\Http\Requests\RegisterUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserController extends Controller
{
    // Connexion d'un administrateur
    public function adminLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/', // Restriction à @gmail.com
            ],
            'password' => 'required|string|size:8', // Taille exacte de 8 caractères
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 401);
        }

        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        $token = $user->createToken('AdminAccessToken')->plainTextToken;

        return response()->json([
            'message' => 'Connexion en tant qu’administrateur réussie.',
            'role' => $user->role,
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    // Connexion utilisateur
    public function login(LogUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $tokenName = $user->role === 'admin' ? 'AdminAccessToken' : 'UserAccessToken';
            $token = $user->createToken($tokenName)->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'message' => 'Connexion avec succès.',
                'role' => $user->role,
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => 'Identifiants incorrects.'], 401);
    }

    // Inscription utilisateur
    public function register(RegisterUser $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'unique:users',
                    'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/', // Restriction à @gmail.com
                ],
                'password' => 'required|string|size:8', // Taille exacte de 8 caractères
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            return response()->json([
                'status_code' => 201,
                'message' => 'Inscription avec succès.',
                'user' => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de l’inscription.', 'error' => $e->getMessage()], 500);
        }
    }

    // Voir les utilisateurs
    public function voir_user()
    {
        try {
            $users = User::all();
            return response()->json(['users' => $users], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des utilisateurs.', 'error' => $e->getMessage()], 500);
        }
    }

    // Déconnexion
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Déconnexion réussie.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la déconnexion.', 'error' => $e->getMessage()], 500);
        }
    }
}