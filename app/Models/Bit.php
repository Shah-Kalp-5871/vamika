<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bit extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'pincodes', 'status'];

    protected $casts = [
        'pincodes' => 'array',
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    /**
     * Get progress for this bit (Visited vs Total Shops).
     * 
     * @param int|null $salespersonId Filter by salesperson
     * @param string|null $date Filter by date (defaults to today)
     * @return array
     */
    public function getProgress($salespersonId = null, $date = null)
    {
        $date = $date ? \Carbon\Carbon::parse($date) : today();
        
        // Base query for active shops in this bit
        $baseQuery = $this->shops()->where('status', 'active');
        
        if ($salespersonId) {
            $baseQuery->where('salesperson_id', $salespersonId);
        }
        
        $totalShops = $baseQuery->count();
        
        if ($totalShops === 0) {
            return ['total' => 0, 'visited' => 0, 'percentage' => 0];
        }

        // Re-query for visited count
        $visitedQuery = $this->shops()->where('status', 'active');
        
        if ($salespersonId) {
            $visitedQuery->where('salesperson_id', $salespersonId);
        }
        
        $visitedShops = $visitedQuery->whereHas('visits', function($q) use ($salespersonId, $date) {
             $q->whereDate('visit_date', $date);
             if ($salespersonId) {
                 $q->where('salesperson_id', $salespersonId);
             }
        })->count();
            
        return [
            'total' => $totalShops,
            'visited' => $visitedShops,
            'percentage' => round(($visitedShops / $totalShops) * 100, 1)
        ];
    }
}
