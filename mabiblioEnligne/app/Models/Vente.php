<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être remplis en masse lors de la création ou modification d'une vente.
     * Permet de sécuriser l'insertion de données depuis un formulaire ou une API.
     */
    protected $fillable = [
        'ouvrage_id', 'quantite', 'prix_unitaire', 'prix_total', 'date_vente'
    ];

    /**
     * Relation : une vente appartient à un ouvrage.
     * Permet de retrouver le livre concerné par la vente.
     */
    public function ouvrage()
    {
        return $this->belongsTo(Ouvrage::class);
    }
}
