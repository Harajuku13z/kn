<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Pressing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'description',
        'opening_hours',
        'is_active',
        'commission_rate',
        'coverage_areas',
        'logo',
        'image',
        'neighborhood',
        'rating',
        'reviews_count',
        'is_express',
        'has_delivery',
        'eco_friendly',
        'delivery_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_express' => 'boolean',
        'has_delivery' => 'boolean',
        'eco_friendly' => 'boolean',
        'commission_rate' => 'float',
        'coverage_areas' => 'json',
        'rating' => 'float',
        'reviews_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pressing) {
            // Generate slug from name if not provided
            if (empty($pressing->slug)) {
                $pressing->slug = Str::slug($pressing->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the services for the pressing.
     */
    public function services(): HasMany
    {
        return $this->hasMany(PressingService::class);
    }

    /**
     * Get the orders for the pressing.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get active services only.
     */
    public function activeServices()
    {
        return $this->services()->where('is_available', true);
    }

    /**
     * Get the formatted commission rate.
     */
    public function getFormattedCommissionRateAttribute()
    {
        return $this->commission_rate . '%';
    }

    /**
     * Get the formatted opening hours.
     */
    public function getFormattedOpeningHoursAttribute()
    {
        return nl2br($this->opening_hours);
    }
}
