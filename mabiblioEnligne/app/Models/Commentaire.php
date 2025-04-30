<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'ouvrage_id', 'auteur', 'contenu', 'note', 'valide'
    ];

    public function ouvrage()
    {
        return $this->belongsTo(Ouvrage::class);
    }
}
