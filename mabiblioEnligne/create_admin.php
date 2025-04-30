<?php
// Ce script crée un utilisateur administrateur directement dans la base de données

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/vendor/autoload.php';

// Chargement de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Utilisation des modèles
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Vérifier si l'utilisateur existe déjà
if (User::where('email', 'superadmin@test.com')->exists()) {
    echo "L'utilisateur superadmin@test.com existe déjà.\n";
} else {
    // Créer un nouvel utilisateur administrateur
    $user = new User();
    $user->name = 'Super Admin';
    $user->email = 'superadmin@test.com';
    $user->password = Hash::make('superadmin123');
    $user->role = 'admin';
    
    if ($user->save()) {
        echo "Utilisateur administrateur créé avec succès.\n";
        echo "Email: superadmin@test.com\n";
        echo "Mot de passe: superadmin123\n";
    } else {
        echo "Erreur lors de la création de l'utilisateur.\n";
    }
}

// Afficher la liste des utilisateurs
echo "\nListe des utilisateurs dans la base de données:\n";
echo "-----------------------------------------------\n";
$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id}, Nom: {$user->name}, Email: {$user->email}, Rôle: {$user->role}\n";
}
