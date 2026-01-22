<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = [
        'salesperson_id', 'shop_id', 'visit_date', 
        'notes', 'location_lat', 'location_lng'
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
