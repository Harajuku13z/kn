<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pressing_id',
        'order_type',
        'quota_id',
        'weight',
        'estimated_price',
        'final_price',
        'pickup_address',
        'delivery_address',
        'pickup_date',
        'delivery_date',
        'pickup_time_slot',
        'delivery_time_slot',
        'special_instructions',
        'pickup_fee',
        'drop_fee',
        'status',
        'payment_method',
        'payment_status',
        'coupon_id',
        'delivery_voucher_id',
        'discount_amount'
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
        'weight' => 'float',
        'estimated_price' => 'integer',
        'final_price' => 'integer',
        'pickup_fee' => 'integer',
        'drop_fee' => 'integer',
        'discount_amount' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the user associated with the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quota(): BelongsTo
    {
        return $this->belongsTo(Quota::class);
    }

    /**
     * Get the coupon applied to this order.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the delivery voucher associated with the order.
     */
    public function deliveryVoucher()
    {
        return $this->belongsTo(DeliveryVoucher::class);
    }

    /**
     * Get the pickup address associated with the order.
     */
    public function pickupAddress()
    {
        return $this->belongsTo(Address::class, 'pickup_address');
    }

    /**
     * Get the delivery address associated with the order.
     */
    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class, 'delivery_address');
    }

    /**
     * Get the pressing for this order.
     */
    public function pressing()
    {
        return $this->belongsTo(Pressing::class);
    }

    /**
     * Get the total delivery fees (pickup + drop).
     *
     * @return int
     */
    public function getTotalDeliveryFeeAttribute()
    {
        return ($this->pickup_fee ?? 0) + ($this->drop_fee ?? 0);
    }

    /**
     * Get the total amount of the order including delivery fees.
     *
     * @return int
     */
    public function getTotalAmountAttribute()
    {
        // Calculate subtotal from order items
        $itemsTotal = 0;
        if ($this->items && $this->items->count() > 0) {
            foreach ($this->items as $item) {
                $itemsTotal += $item->unit_price * $item->quantity;
            }
        }
        
        // Get the base price (either already includes items or it's stored directly)
        $basePrice = $this->final_price ?? $this->estimated_price ?? $itemsTotal;
        
        // Add delivery fees
        $deliveryFees = $this->total_delivery_fee;
        
        // Return total
        return $basePrice + $deliveryFees;
    }

    /**
     * Get the total amount - alias for getTotalAmountAttribute for backward compatibility
     *
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->total_amount;
    }
}
