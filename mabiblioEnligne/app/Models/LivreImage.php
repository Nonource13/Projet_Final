<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivreImage extends Model
{
    use HasFactory;

    protected $fillable = ['ouvrage_id', 'chemin_image', 'is_principale'];

    public function ouvrage()
    {
        return $this->belongsTo(Ouvrage::class);
    }
}
