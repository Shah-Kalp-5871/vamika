<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'area_id', 'salesperson_id', 'name', 'address', 'phone', 
        'status', 'credit_limit', 'current_balance'
    ];

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
