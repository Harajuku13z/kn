<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quota extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_kg',
        'used_kg',
        'type',
        'price',
        'expiration_date',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_kg' => 'float',
        'used_kg' => 'float',
        'is_active' => 'boolean',
        'expiration_date' => 'datetime',
    ];

    /**
     * Get the user that owns the quota.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders that use this quota.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the subscription associated with this quota.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the available kilograms in this quota.
     */
    public function getAvailableKgAttribute()
    {
        return $this->total_kg - $this->used_kg;
    }

    /**
     * Check if the quota is available.
     */
    public function getIsAvailableAttribute()
    {
        return $this->is_active && 
               ($this->expiration_date === null || $this->expiration_date > now()) && 
               $this->available_kg > 0;
    }

    /**
     * Use a certain amount of kilograms from this quota.
     */
    public function use(float $kg)
    {
        if ($kg > $this->available_kg) {
            throw new \Exception('Not enough quota available');
        }

        $this->used_kg += $kg;
        $this->save();

        return $this;
    }
}
