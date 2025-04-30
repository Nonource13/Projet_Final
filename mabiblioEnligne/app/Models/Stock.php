<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['ouvrage_id', 'quantite'];

    public function ouvrage()
    {
        return $this->belongsTo(Ouvrage::class);
    }
}
