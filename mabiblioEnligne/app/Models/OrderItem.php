<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'ouvrage_id',
        'quantity',
        'price',
        'subtotal',
        'gift_list_item',
        'gift_list_id',
    ];

    /**
     * Les attributs qui doivent être mutés en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'gift_list_item' => 'boolean',
    ];

    /**
     * Relation avec la commande.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
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

    /**
     * Relation avec la liste de cadeaux.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giftList()
    {
        return $this->belongsTo(GiftList::class);
    }
}
