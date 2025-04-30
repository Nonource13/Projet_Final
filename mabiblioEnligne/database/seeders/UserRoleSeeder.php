<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un utilisateur Éditeur
        if (!User::where('email', 'editeur@test.com')->exists()) {
            User::create([
                'name' => 'Editeur Test',
                'email' => 'editeur@test.com',
                'password' => Hash::make('editeur123'),
                'role' => 'editor',
            ]);
            $this->command->info('Utilisateur Éditeur créé avec succès !');
        } else {
            $this->command->info('L\'utilisateur Éditeur existe déjà.');
        }

        // Créer un utilisateur Gestionnaire
        if (!User::where('email', 'gestionnaire@test.com')->exists()) {
            User::create([
                'name' => 'Gestionnaire Test',
                'email' => 'gestionnaire@test.com',
                'password' => Hash::make('gestionnaire123'),
                'role' => 'manager',
            ]);
            $this->command->info('Utilisateur Gestionnaire créé avec succès !');
        } else {
            $this->command->info('L\'utilisateur Gestionnaire existe déjà.');
        }

        // Créer un utilisateur Administrateur
        if (!User::where('email', 'admin@test.com')->exists()) {
            User::create([
                'name' => 'Administrateur Test',
                'email' => 'admin@test.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
            $this->command->info('Utilisateur Administrateur créé avec succès !');
        } else {
            $this->command->info('L\'utilisateur Administrateur existe déjà.');
        }
    }
}
