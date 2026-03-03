<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['shop-owner', 'salesperson'])
            ->update(['creator_type' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without knowing the previous state, 
        // but since they were likely 'self' by default, we can set them back to 'self'.
        DB::table('users')
            ->whereIn('role', ['shop-owner', 'salesperson'])
            ->update(['creator_type' => 'self']);
    }
};
