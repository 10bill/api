<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function promoteToSuperAdmin(Request $request, $id)
{
    $currentUser = auth()->user();

    // Vérifiez si l'utilisateur actuel est un super_admin
    if ($currentUser->role !== 'super_admin') {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    $admin = User::find($id);
    if (!$admin || $admin->role !== 'admin') {
        return response()->json(['message' => 'Administrateur non trouvé ou non valide.'], 404);
    }

    $admin->role = 'super_admin';
    $admin->save();

    return response()->json(['message' => 'Administrateur promu au rôle de super_admin avec succès.'], 200);
}

public function createAdmin(Request $request)
{
    // Validation des champs
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
    ]);

    $currentUser = auth()->user();

    // Vérifiez que seul un super_admin peut créer un nouvel admin
    if ($currentUser->role !== 'super_admin') {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    // Création de l'admin
    $admin = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'admin',
    ]);

    return response()->json(['message' => 'Nouvel administrateur créé avec succès.', 'admin' => $admin], 201);
}

    // Inscription d'un administrateur
    public function registerAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin', // Rôle administrateur défini ici
        ]);

        return response()->json([
            'message' => 'Administrateur créé avec succès.',
            'user' => $admin,
        ], 201);
    }

    // Connexion d'un administrateur
    public function loginAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = User::where('email', $validated['email'])->first();

        if (!$admin || !Hash::check($validated['password'], $admin->password)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 401);
        }

        if ($admin->role !== 'admin' && $admin->role !== 'super_admin') {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        $token = $admin->createToken('AdminAccessToken')->plainTextToken;

        return response()->json([
            'message' => 'Connexion administrateur réussie.',
            'token' => $token,
            'user' => $admin,
        ], 200);
    }

    // Gérer les utilisateurs (liste, ajout, suppression, modification)
    public function listUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    // Liste des utilisateurs
    public function voir_user()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    // Ajouter un utilisateur
    public function create_user(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json(['message' => 'Utilisateur ajouté avec succès.', 'user' => $user], 201);
    }

    // Modifier un utilisateur
    public function update_user(Request $request, $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }
    
        // Valider les données
        $validated = $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:6', // Validation du mot de passe
            'role' => 'in:user,admin',
        ]);
    
        // Vérifier si le mot de passe est présent, le crypter avant la mise à jour
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
    
        // Mettre à jour les données de l'utilisateur
        $user->update(array_filter($validated));
    
        return response()->json(['message' => 'Utilisateur modifié avec succès.', 'user' => $user], 200);
    }
    
    // Supprimer un utilisateur

    public function delete_user($id)
{
    $currentUser = auth()->user();

    // Vérifiez le rôle
    if ($currentUser->role !== 'admin' && $currentUser->role !== 'super_admin') {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'Utilisateur supprimé avec succès.'], 200);
}

}