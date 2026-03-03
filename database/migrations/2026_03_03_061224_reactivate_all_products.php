<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reactivate all products that might have been deactivated by the old stock listener
        DB::table('products')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No logical way to reverse without a previous state record, but usually keep as is or set inactive if zero
        // DB::table('products')->where('stock', '<=', 0)->update(['status' => 'inactive']);
    }
};
