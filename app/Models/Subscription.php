<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subscription_type_id',
        'purchase_date',
        'expiration_date',
        'quota_purchased',
        'amount_paid',
        'payment_method',
        'payment_status',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'datetime',
        'expiration_date' => 'datetime',
        'quota_purchased' => 'float',
        'amount_paid' => 'integer'
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription type for this subscription.
     */
    public function subscriptionType(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    /**
     * Get the quota usages associated with this subscription.
     */
    public function quotaUsages(): HasMany
    {
        return $this->hasMany(QuotaUsage::class);
    }

    /**
     * Check if this subscription is active.
     */
    public function getIsActiveAttribute(): bool
    {
        // Un abonnement est actif si la date d'expiration n'est pas dépassée ET qu'il reste du quota
        return ($this->payment_status === 'paid' || $this->payment_status === 'pending') &&
               ($this->expiration_date === null || $this->expiration_date > now()) &&
               ($this->remaining_quota > 0);
    }

    /**
     * Get the remaining quota for this subscription.
     */
    public function getRemainingQuotaAttribute(): float
    {
        $usedQuota = $this->quotaUsages()->sum('amount_used');
        return max(0, $this->quota_purchased - $usedQuota);
    }

    /**
     * Format the payment method for display.
     */
    public function getFormattedPaymentMethodAttribute(): string
    {
        return match($this->payment_method) {
            'card' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money',
            'cash' => 'Paiement à la livraison/collecte',
            default => $this->payment_method
        };
    }

    /**
     * Format the payment status for display.
     */
    public function getFormattedPaymentStatusAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'En attente',
            'paid' => 'Payé',
            'cancelled' => 'Annulé',
            default => $this->payment_status
        };
    }
}
