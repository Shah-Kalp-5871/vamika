<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration ensures the bit_id column exists in the users table.
     * It's safe to run multiple times - only adds the column if it doesn't exist.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if employee_id exists, if not add it
            if (!Schema::hasColumn('users', 'employee_id')) {
                $column = $table->string('employee_id')->nullable();
                if (Schema::hasColumn('users', 'status')) {
                    $column->after('status');
                }
            }
            
            // Check if bit_id exists, if not add it
            if (!Schema::hasColumn('users', 'bit_id')) {
                $column = $table->foreignId('bit_id')->nullable()->constrained('bits')->nullOnDelete();
                if (Schema::hasColumn('users', 'employee_id')) {
                    $column->after('employee_id');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bit_id')) {
                $table->dropForeign(['bit_id']);
                $table->dropColumn('bit_id');
            }
            if (Schema::hasColumn('users', 'employee_id')) {
                $table->dropColumn('employee_id');
            }
        });
    }
};
