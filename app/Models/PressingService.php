<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PressingService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pressing_id',
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
        'estimated_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'integer',
        'estimated_time' => 'integer',
    ];

    /**
     * Get the pressing that owns the service.
     */
    public function pressing(): BelongsTo
    {
        return $this->belongsTo(Pressing::class);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the formatted estimated time.
     */
    public function getFormattedTimeAttribute()
    {
        if (!$this->estimated_time) {
            return 'Non spécifié';
        }
        
        if ($this->estimated_time < 24) {
            return $this->estimated_time . ' heure(s)';
        }
        
        $days = floor($this->estimated_time / 24);
        $hours = $this->estimated_time % 24;
        
        if ($hours == 0) {
            return $days . ' jour(s)';
        }
        
        return $days . ' jour(s) et ' . $hours . ' heure(s)';
    }

    /**
     * Get image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        
        return asset('images/default-service.png');
    }

    /**
     * Get the order items for this service.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
