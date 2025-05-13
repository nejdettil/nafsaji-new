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
        // تصحيح سجل المهاجرات للجداول الأساسية
        if (Schema::hasTable('migrations')) {
            // التحقق من وجود سجلات معينة وإضافتها إذا كانت غير موجودة
            $migrations = [
                ['migration' => '0001_01_01_000000_create_users_table', 'batch' => 1],
                ['migration' => '0001_01_01_000001_create_cache_table', 'batch' => 1],
                ['migration' => '0001_01_01_000002_create_jobs_table', 'batch' => 1],
                ['migration' => '2024_05_11_000000_create_notifications_table', 'batch' => 1],
                ['migration' => '2025_05_10_182123_create_nafsaji_sessions_table', 'batch' => 1],
                ['migration' => '2025_05_11_235704_add_booking_price_fields_table', 'batch' => 1],
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
        
        // التحقق من وجود حقول ضرورية في جدول المستخدمين
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
                
                if (!Schema::hasColumn('users', 'active')) {
                    $table->boolean('active')->default(true)->after('last_login_at');
                }
                
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('active');
                }
            });
        }
        
        // التحقق من وجود حقول ضرورية في جدول الحجوزات
        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('payment_status')->default('pending')->after('price');
                $table->string('payment_method')->nullable()->after('payment_status');
                $table->string('payment_id')->nullable()->after('payment_method');
            });
        }
        
        // أزلنا الجزء المتعلق بجدول المتخصصين لأن بنية الجدول مختلفة عما توقعنا
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا يوجد تغييرات للتراجع عنها
    }
};
