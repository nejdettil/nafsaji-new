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
        // تحقق من وجود جدول permissions قبل محاولة تعديله
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                // تحقق من عدم وجود العمود مسبقاً
                if (!Schema::hasColumn('permissions', 'description')) {
                    $table->string('description')->nullable()->after('guard_name');
                }
            });
        }
        
        // تحقق من وجود جدول roles قبل محاولة تعديله
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                // تحقق من عدم وجود العمود مسبقاً
                if (!Schema::hasColumn('roles', 'description')) {
                    $table->string('description')->nullable()->after('guard_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // تحقق من وجود جدول permissions والعمود قبل حذفه
        if (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'description')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
        
        // تحقق من وجود جدول roles والعمود قبل حذفه
        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'description')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
