<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id', 'salesperson_id', 'total_amount', 
        'status', 'payment_status', 'notes'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
