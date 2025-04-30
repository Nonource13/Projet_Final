<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    /**
     * Affiche la liste des utilisateurs.
     * Récupère tous les utilisateurs et les affiche dans la vue users.index.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur.
     * Permet d'afficher un formulaire pour ajouter un nouvel utilisateur.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Enregistre un nouvel utilisateur dans la base de données.
     * Valide les données et crée un nouvel utilisateur avec un rôle.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|string'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté.');
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     * Permet de modifier les informations d'un utilisateur existant.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * Valide les données puis applique les modifications sur l'utilisateur existant.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|string'
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('users.index')->with('success', 'Utilisateur modifié.');
    }

    /**
     * Supprime un utilisateur de la base de données.
     * Permet de retirer un utilisateur définitivement.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
