<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This comprehensive migration ensures ALL required columns exist across all tables.
     * Safe to run multiple times - only adds missing columns.
     */
    public function up(): void
    {
        // Fix USERS table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
            if (!Schema::hasColumn('users', 'employee_id')) {
                $column = $table->string('employee_id')->nullable();
                if (Schema::hasColumn('users', 'status')) {
                    $column->after('status');
                }
            }
            if (!Schema::hasColumn('users', 'bit_id')) {
                $column = $table->foreignId('bit_id')->nullable()->constrained('bits')->nullOnDelete();
                if (Schema::hasColumn('users', 'employee_id')) {
                    $column->after('employee_id');
                }
            }
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

        // Fix SHOPS table
        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'bit_id')) {
                $column = $table->foreignId('bit_id')->constrained('bits')->onDelete('cascade');
                if (Schema::hasColumn('shops', 'user_id')) {
                    $column->after('user_id');
                }
            }
            if (!Schema::hasColumn('shops', 'salesperson_id')) {
                $column = $table->foreignId('salesperson_id')->nullable()->constrained('users')->onDelete('set null');
                if (Schema::hasColumn('shops', 'bit_id')) {
                    $column->after('bit_id');
                }
            }
        });

        // Fix PRODUCTS table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'division')) {
                $table->string('division')->nullable()->after('brand');
            }
            if (!Schema::hasColumn('products', 'sub_brand')) {
                $table->string('sub_brand')->nullable()->after('division');
            }
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit')->nullable()->after('sub_brand');
            }
        });

        // Fix VISITS table
        Schema::table('visits', function (Blueprint $table) {
            if (!Schema::hasColumn('visits', 'status')) {
                $table->enum('status', ['completed', 'pending', 'cancelled'])->default('pending')->after('visit_date');
            }
            if (!Schema::hasColumn('visits', 'no_order_reason')) {
                $table->text('no_order_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('visits', 'order_id')) {
                $column = $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
                if (Schema::hasColumn('visits', 'no_order_reason')) {
                    $column->after('no_order_reason');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse is optional for this fix migration
        // since it's meant to reconcile existing schema differences
    }
};
