<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
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
        'price',
        'pressing_id',
        'category_id',
        'is_active',
        'image',
        'estimated_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the pressing that offers this service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pressing(): BelongsTo
    {
        return $this->belongsTo(Pressing::class);
    }

    /**
     * Get the category of this service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
} 