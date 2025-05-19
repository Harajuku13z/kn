<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceConfiguration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price_per_kg',
        'last_update_reason',
        'effective_date',
        'created_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'effective_date' => 'datetime',
        'price_per_kg' => 'decimal:2',
    ];

    /**
     * Get the user that created the price configuration.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the current price configuration.
     */
    public static function getCurrentPrice()
    {
        return static::where('effective_date', '<=', now())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get the current price per kg with a default value.
     */
    public static function getCurrentPricePerKg($default = 1000)
    {
        $price = static::getCurrentPrice();
        return $price ? $price->price_per_kg : $default;
    }
} 