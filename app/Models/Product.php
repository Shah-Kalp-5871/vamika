<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'sku', 'description', 'price', 
        'category', 'status'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper to get primary image
    public function getPrimaryImageAttribute()
    {
        return $this->images->where('is_primary', true)->first()->image_path ?? null;
    }
}
