<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryFee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'city_id',
        'district',
        'fee',
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
        'fee' => 'integer',
    ];

    /**
     * Get the city that owns the delivery fee.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the formatted fee.
     */
    public function getFormattedFeeAttribute()
    {
        return number_format($this->fee, 0, ',', ' ') . ' FCFA';
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
     * Get fee for a specific district within a city.
     * 
     * @param string $district
     * @param int|null $city_id
     * @return int
     */
    public static function getFeeForDistrict($district, $city_id = null)
    {
        $query = self::where('district', $district)
            ->where('is_active', true);
            
        if ($city_id) {
            $query->where('city_id', $city_id);
        }
        
        $deliveryFee = $query->first();
        
        return $deliveryFee ? $deliveryFee->fee : 750; // Default fee if not found
    }

    /**
     * Alias for getFeeForDistrict for backward compatibility.
     * 
     * @param string $neighborhood
     * @param int|null $city_id
     * @return int
     */
    public static function getFeeForNeighborhood($neighborhood, $city_id = null)
    {
        return self::getFeeForDistrict($neighborhood, $city_id);
    }

    /**
     * Calculate combined delivery fee for pickup and delivery between two districts.
     * 
     * @param string $pickupDistrict
     * @param string $deliveryDistrict
     * @param int|null $pickupCityId
     * @param int|null $deliveryCityId
     * @return int
     */
    public static function calculateCombinedFee($pickupDistrict, $deliveryDistrict, $pickupCityId = null, $deliveryCityId = null)
    {
        $pickupFee = self::getFeeForDistrict($pickupDistrict, $pickupCityId);
        $deliveryFee = self::getFeeForDistrict($deliveryDistrict, $deliveryCityId);
        
        // If pickup and delivery are in the same district and city, apply a single fee
        if ($pickupDistrict === $deliveryDistrict && $pickupCityId === $deliveryCityId) {
            return $pickupFee;
        }
        
        return $pickupFee + $deliveryFee;
    }
}
