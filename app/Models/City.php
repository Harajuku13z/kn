<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the delivery fees for the city.
     */
    public function deliveryFees(): HasMany
    {
        return $this->hasMany(DeliveryFee::class);
    }
    
    /**
     * Get all active districts/neighborhoods for this city.
     */
    public function activeDistricts()
    {
        return $this->deliveryFees()
            ->where('is_active', true)
            ->orderBy('district')
            ->get();
    }

    /**
     * Get all districts names as array.
     */
    public function getDistrictsAttribute()
    {
        return $this->deliveryFees->pluck('district')->toArray();
    }
}
