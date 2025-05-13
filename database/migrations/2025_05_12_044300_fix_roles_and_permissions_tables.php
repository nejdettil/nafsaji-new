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
        // تصحيح جدول الأدوار إذا كان موجودًا
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'description')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('description')->nullable()->after('guard_name');
            });
        }
        
        // تصحيح جدول الصلاحيات إذا كان موجودًا
        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'description')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('description')->nullable()->after('guard_name');
            });
        }
        
        // تصحيح سجل المهاجرات لتعكس وجود الجداول
        if (Schema::hasTable('migrations')) {
            // التحقق من وجود سجلات معينة وإضافتها إذا كانت غير موجودة
            $migrations = [
                ['migration' => '2025_05_12_020000_add_role_to_users_table', 'batch' => 1],
                ['migration' => '2025_05_12_041200_create_settings_table', 'batch' => 1],
                ['migration' => '2025_05_12_042500_add_description_to_permissions', 'batch' => 1],
                ['migration' => '2025_05_10_182122_create_specialists_table', 'batch' => 1],
                ['migration' => '2025_05_10_182123_create_bookings_table', 'batch' => 1],
                ['migration' => '2025_05_10_182123_create_services_table', 'batch' => 1],
                ['migration' => '2025_05_10_190538_create_contacts_table', 'batch' => 1],
                ['migration' => '2025_05_11_235539_add_price_and_payment_fields_to_bookings_table', 'batch' => 1],
            ];
            
            foreach ($migrations as $migration) {
                $exists = DB::table('migrations')
                    ->where('migration', $migration['migration'])
                    ->exists();
                    
                if (!$exists) {
                    DB::table('migrations')->insert([
                        'migration' => $migration['migration'],
                        'batch' => $migration['batch'],
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا يوجد تغييرات للتراجع عنها
    }
};
