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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'work_start_time')) {
                $column = $table->time('work_start_time')->nullable();
                if (Schema::hasColumn('users', 'bit_id')) {
                    $column->after('bit_id');
                }
            }

            if (!Schema::hasColumn('users', 'work_end_time')) {
                $table->time('work_end_time')->nullable()->after('work_start_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['work_start_time', 'work_end_time']);
        });
    }
};
