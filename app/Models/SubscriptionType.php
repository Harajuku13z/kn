<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quota',
        'duration',
        'price',
        'service_level',
        'service_features',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'service_features' => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
    /**
     * Get the formatted service level name
     */
    public function getFormattedServiceLevelAttribute(): string
    {
        return match($this->service_level) {
            'standard' => 'Standard',
            'priority' => 'Prioritaire',
            'express' => 'Express',
            default => ucfirst($this->service_level)
        };
    }
} 