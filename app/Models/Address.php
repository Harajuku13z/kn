<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'city_id',
        'name',
        'address',
        'district',
        'landmark',
        'phone',
        'contact_name',
        'type',
        'is_default',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the city that owns the address.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope a query to only include default addresses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    
    /**
     * Get the full district name with city (for display purposes)
     */
    public function getFullDistrictNameAttribute()
    {
        if ($this->city) {
            return $this->city->name . ' - ' . $this->district;
        }
        return $this->district;
    }
    
    /**
     * Get the city name (for display purposes)
     */
    public function getCityNameAttribute()
    {
        return $this->city ? $this->city->name : 'Non défini';
    }
} 