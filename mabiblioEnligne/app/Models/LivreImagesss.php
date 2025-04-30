<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivreImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre','genre' ,'anneedepublication','photo'
    ];
}
