<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryVoucher extends Model
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
        'user_id',
        'number_of_deliveries',
        'used_deliveries',
        'valid_from',
        'valid_until',
        'is_active',
        'reason',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who owns this voucher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who created this voucher.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the orders that used this voucher.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_voucher_id');
    }

    /**
     * Check if the voucher is valid.
     */
    public function isValid(): bool
    {
        // Vérifier si le voucher est actif
        if (!$this->is_active) {
            return false;
        }

        // Vérifier les dates de validité
        $now = now();
        if ($now < $this->valid_from || ($this->valid_until && $now > $this->valid_until)) {
            return false;
        }

        // Vérifier le nombre d'utilisations
        if ($this->used_deliveries >= $this->number_of_deliveries) {
            return false;
        }

        return true;
    }

    /**
     * Get the number of remaining deliveries.
     */
    public function getRemainingDeliveriesAttribute(): int
    {
        return max(0, $this->number_of_deliveries - $this->used_deliveries);
    }

    /**
     * Use one delivery from this voucher.
     */
    public function useDelivery(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->used_deliveries += 1;
        $this->save();

        return true;
    }

    /**
     * Get vouchers available for a specific user.
     * 
     * @param int $userId The user ID to check
     * @return array Array of available vouchers with additional attributes
     */
    public static function getAvailableForUser(int $userId): array
    {
        $vouchers = self::where('user_id', $userId)
            ->where('is_active', true)
            ->whereDate('valid_from', '<=', now())
            ->where(function($query) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', now());
            })
            ->whereRaw('used_deliveries < number_of_deliveries')
            ->get();
            
        return $vouchers->map(function($voucher) {
            return [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'description' => $voucher->description,
                'remaining_deliveries' => $voucher->remaining_deliveries,
                'valid_until_formatted' => $voucher->valid_until ? $voucher->valid_until->format('d/m/Y') : 'Illimité'
            ];
        })->toArray();
    }
}
