<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse (lors de la création ou modification).
     * Permet de protéger contre l'injection de champs non désirés.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'date_inscription',
        'actif'
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     * Permet de manipuler ces champs comme des objets DateTime.
     *
     * @var array
     */
    protected $dates = [
        'date_inscription',
        'created_at',
        'updated_at'
    ];

    /**
     * Les attributs qui doivent être mutés en types natifs.
     * Par exemple, 'actif' sera automatiquement converti en booléen.
     *
     * @var array
     */
    protected $casts = [
        'actif' => 'boolean',
    ];

    // Exemple de relation possible :
    // Un client peut avoir plusieurs commandes
    // public function commandes()
    // {
    //     return $this->hasMany(Order::class);
    // }
}
