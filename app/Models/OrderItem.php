<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'pressing_service_id',
        'service_id',
        'quantity',
        'weight',
        'unit_price'
    ];

    protected $casts = [
        'weight' => 'float',
        'unit_price' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the pressing service for this order item.
     */
    public function pressingService()
    {
        return $this->belongsTo(PressingService::class);
    }
    
    /**
     * Get the new service model for this order item.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
