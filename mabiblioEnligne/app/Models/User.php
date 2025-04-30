<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modèle représentant un utilisateur dans l'application.
 * Il hérite des fonctionnalités de base d'un utilisateur authentifiable.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Champs modifiables en masse (création ou modification d'utilisateur).
     * Permet d'assurer la sécurité lors de l'insertion de données.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * Champs cachés lors des réponses API (jamais exposés côté client).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Définition des types natifs pour certains champs.
     * Par exemple, 'email_verified_at' sera automatiquement converti en objet DateTime.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Vérification du rôle utilisateur (admin, éditeur, gestionnaire, etc.).
     * Permet de gérer les droits d'accès dans l'application.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        // Conversion des différentes variations de noms de rôles
        $adminRoles = ['admin', 'administrateur'];
        $editorRoles = ['editor', 'editeur', 'éditeur'];
        $managerRoles = ['manager', 'gestionnaire'];
        
        if (in_array($role, $adminRoles)) {
            return in_array($this->role, $adminRoles);
        }
        
        if (in_array($role, $editorRoles)) {
            return in_array($this->role, $editorRoles);
        }
        
        if (in_array($role, $managerRoles)) {
            return in_array($this->role, $managerRoles);
        }
        
        return $this->role === $role;
    }

    // Exemple de relation possible :
    // Un utilisateur peut avoir plusieurs commandes
    // public function commandes()
    // {
    //     return $this->hasMany(Order::class);
    // }
}
