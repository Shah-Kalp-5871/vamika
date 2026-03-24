<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'sku', 'description', 'price', 
        'category', 'status', 'unit', 'mrp', 'stock'
    ];

    protected $appends = ['image_url'];

    protected static function booted()
    {
        // Removed auto-deactivation on zero stock
    }

    public const CATEGORIES = [
        'durby' => 'Durby',
        'forolly' => 'Forolly',
        'million' => 'Million',
        'michi-s' => "Michi's",
        'oshon' => 'Oshon',
        'crazzy-s' => "Crazzy's",
        'ankit' => 'Ankit',
        'mayora' => 'Mayora',
        'confito' => 'Confito',
        'bakemate' => 'Bakemate',
        'others' => 'Others'
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
        $image = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        return $image ? $image->image_path : null;
    }

    public function getImageUrlAttribute()
    {
        $path = $this->primary_image;
        return $path ? asset('storage/' . $path) : null;
    }

}
