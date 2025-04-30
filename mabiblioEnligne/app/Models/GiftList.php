<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GiftList extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'occasion',
        'event_date',
        'access_code',
        'is_public',
        'expiry_date',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'event_date',
        'expiry_date',
        'created_at',
        'updated_at',
    ];

    /**
     * Les attributs qui doivent être mutés en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Génère un code d'accès unique.
     *
     * @return string
     */
    public static function generateAccessCode()
    {
        do {
            $code = Str::random(8);
        } while (self::where('access_code', $code)->exists());

        return $code;
    }

    /**
     * Relation avec l'utilisateur propriétaire de la liste.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les ouvrages de la liste.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ouvrages()
    {
        return $this->belongsToMany(Ouvrage::class, 'gift_list_items')
            ->withPivot('quantity', 'priority', 'is_reserved', 'reserved_by')
            ->withTimestamps();
    }
}
