<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'sku', 'description', 'price', 
        'category', 'status', 'brand', 'division', 
        'sub_brand', 'unit', 'mrp'
    ];

    public const CATEGORIES = [
        'food' => 'Food',
        'beverages' => 'Beverages',
        'personal-care' => 'Personal Care',
        'home-care' => 'Home Care',
        'snacks' => 'Snacks',
        'dairy' => 'Dairy',
        'staples' => 'Staples',
        'household' => 'Household',
        'bakery' => 'Bakery',
        'frozen' => 'Frozen',
        'others' => 'Others'
    ];

    public const BRANDS = [
        'ashirwad' => 'Ashirwad',
        'tata' => 'Tata',
        'amul' => 'Amul',
        'coca-cola' => 'Coca-Cola',
        'lays' => 'Lays',
        'parle' => 'Parle',
        'india-gate' => 'India Gate',
        'fortune' => 'Fortune',
        'madhur' => 'Madhur',
        'colgate' => 'Colgate',
        'surf-excel' => 'Surf Excel',
        'taj-mahal' => 'Taj Mahal',
        'others' => 'Others'
    ];

    public const SUB_BRANDS = [
        'select' => 'Select',
        'premium' => 'Premium',
        'regular' => 'Regular',
        'gold' => 'Gold',
        'fresh' => 'Fresh',
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
        return $this->images->where('is_primary', true)->first()->image_path ?? null;
    }
}
