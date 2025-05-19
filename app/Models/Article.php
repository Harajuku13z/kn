<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'weight_text',
        'type',
        'usage',
        'weight_class',
        'average_weight',
        'price',
        'price_text',
        'is_active'
    ];

    protected $casts = [
        'type' => 'array',
        'usage' => 'array',
        'average_weight' => 'float',
        'price' => 'float',
        'is_active' => 'boolean'
    ];
} 