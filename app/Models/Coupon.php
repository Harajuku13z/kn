<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'min_order_amount',
        'max_discount_amount',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'float',
        'min_order_amount' => 'float',
        'max_discount_amount' => 'float',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the admin who created this coupon.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the orders that used this coupon.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid(): bool
    {
        // Vérifier si le coupon est actif
        if (!$this->is_active) {
            return false;
        }

        // Vérifier les dates de validité
        $now = now();
        if ($now < $this->valid_from || ($this->valid_until && $now > $this->valid_until)) {
            return false;
        }

        // Vérifier le nombre d'utilisations
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount based on the order total.
     */
    public function calculateDiscount(float $orderTotal): float
    {
        // Vérifier le montant minimum de commande
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return 0;
        }

        // Calculer la remise
        $discount = 0;
        if ($this->type === 'percentage') {
            $discount = $orderTotal * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        // Appliquer le plafond de remise si défini
        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        // S'assurer que la remise ne dépasse pas le montant total de la commande
        return min($discount, $orderTotal);
    }

    /**
     * Get available coupons for a user.
     */
    public static function getAvailableForUser(?int $userId = null): array
    {
        $now = now();
        
        $query = self::where('is_active', true)
            ->where(function($query) use ($now) {
                $query->where(function($q) use ($now) {
                    $q->whereNull('valid_until')
                      ->where('valid_from', '<=', $now);
                })->orWhere(function($q) use ($now) {
                    $q->where('valid_from', '<=', $now)
                      ->where('valid_until', '>=', $now);
                });
            })
            ->where(function($query) {
                $query->whereNull('max_uses')
                      ->orWhereRaw('used_count < max_uses');
            })
            ->orderBy('valid_until', 'asc')
            ->get();
        
        return $query->map(function($coupon) {
            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'description' => $coupon->description,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'formatted_value' => $coupon->type === 'percentage' 
                    ? number_format($coupon->value, 0) . '%' 
                    : number_format($coupon->value, 0) . ' FCFA',
                'min_order_amount' => $coupon->min_order_amount,
                'max_discount_amount' => $coupon->max_discount_amount,
                'remaining_uses' => $coupon->max_uses ? ($coupon->max_uses - $coupon->used_count) : null,
                'valid_until' => $coupon->valid_until,
                'valid_until_formatted' => $coupon->valid_until ? $coupon->valid_until->format('d/m/Y') : 'Sans limite'
            ];
        })->toArray();
    }
}
