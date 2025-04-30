<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ouvrage extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être remplis en masse lors de la création ou modification d'un ouvrage.
     * Permet d'assurer la sécurité lors des opérations de masse.
     */
    protected $fillable = [
        'titre', 'auteur', 'description', 'niveau_expertise',
        'categorie_id', 'prix', 'date_publication'
    ];

    /**
     * Relation : un ouvrage appartient à une catégorie.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Relation : un ouvrage a un stock associé.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Relation : un ouvrage peut avoir plusieurs ventes.
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    /**
     * Relation : un ouvrage peut avoir plusieurs commentaires (avis clients).
     */
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    /**
     * Relation : un ouvrage peut avoir plusieurs images associées.
     */
    public function images()
    {
        return $this->hasMany(LivreImage::class);
    }
}
