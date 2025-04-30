<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftListItem extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'gift_list_id',
        'ouvrage_id',
        'quantity',
        'priority', // Priorité de 1 à 5 (1 étant la plus haute)
        'is_reserved', // Indique si l'article est réservé par un ami
        'reserved_by', // Nom de la personne qui a réservé l'article
    ];

    /**
     * Les attributs qui doivent être mutés en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'is_reserved' => 'boolean',
    ];

    /**
     * Relation avec la liste de cadeaux.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giftList()
    {
        return $this->belongsTo(GiftList::class);
    }

    /**
     * Relation avec l'ouvrage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ouvrage()
    {
        return $this->belongsTo(Ouvrage::class);
    }
}
