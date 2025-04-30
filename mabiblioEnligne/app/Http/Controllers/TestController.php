<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    /**
     * Afficher les utilisateurs et leurs rôles pour le débogage
     */
    public function showUsers()
    {
        $users = User::all();
        return view('test.users', compact('users'));
    }

    /**
     * Créer un utilisateur de test pour chaque rôle
     */
    public function createTestUsers()
    {
        // Créer un utilisateur administrateur
        if (!User::where('email', 'admin2@test.com')->exists()) {
            User::create([
                'name' => 'Admin Test',
                'email' => 'admin2@test.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        // Créer un utilisateur éditeur
        if (!User::where('email', 'editeur2@test.com')->exists()) {
            User::create([
                'name' => 'Editeur Test',
                'email' => 'editeur2@test.com',
                'password' => Hash::make('editeur123'),
                'role' => 'editor',
            ]);
        }

        // Créer un utilisateur gestionnaire
        if (!User::where('email', 'gestionnaire2@test.com')->exists()) {
            User::create([
                'name' => 'Gestionnaire Test',
                'email' => 'gestionnaire2@test.com',
                'password' => Hash::make('gestionnaire123'),
                'role' => 'manager',
            ]);
        }

        return redirect()->route('test.users')->with('success', 'Utilisateurs de test créés avec succès !');
    }
}
