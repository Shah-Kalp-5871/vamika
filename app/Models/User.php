<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'status',
        'employee_id',
        'bit_id',
        'work_start_time',
        'work_end_time',
    ];

    public function managedShops()
    {
        return $this->hasMany(Shop::class, 'salesperson_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bit()
    {
        return $this->belongsTo(Bit::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function salespersonOrders()
    {
        return $this->hasMany(Order::class, 'salesperson_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'salesperson_id');
    }

    public function isSalesperson()
    {
        return $this->role === 'salesperson';
    }

    public function isShopOwner()
    {
        return $this->role === 'shop-owner';
    }
}