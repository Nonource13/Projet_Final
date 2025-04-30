<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse (création ou modification).
     * Permet de sécuriser l'insertion de données depuis un formulaire ou une API.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_postal_code',
        'billing_country',
        'notes',
    ];

    /**
     * Génère un numéro de commande unique au format LG-YYYYMMDD-XXXXXX.
     * Permet d'identifier chaque commande de façon sûre.
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        do {
            $number = 'LG-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Relation : une commande appartient à un utilisateur.
     * Permet de retrouver le client ayant passé la commande.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : une commande possède plusieurs lignes de commande (OrderItem).
     * Chaque ligne correspond à un ouvrage acheté.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Vérifie si la commande est en attente (status = 'pending').
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifie si la commande est confirmée (status = 'confirmed').
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Vérifie si la commande est en cours de traitement (status = 'processing').
     *
     * @return bool
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Vérifie si la commande a été livrée (status = 'delivered').
     *
     * @return bool
     */
    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    /**
     * Vérifie si la commande est annulée (status = 'cancelled').
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
