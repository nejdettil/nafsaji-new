<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إنشاء مستخدم جديد للمتخصص التجريبي إذا لم يكن موجودًا
        $userId = DB::table('users')->insertGetId([
            'name' => 'متخصص تجريبي',
            'email' => 'test.specialist@nafsaji.test',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // إنشاء متخصص تجريبي
        $specialistId = DB::table('specialists')->insertGetId([
            'user_id' => $userId,
            'specialization' => 'علم النفس، استشارات نفسية',
            'qualification' => 'دكتوراه في علم النفس - جامعة تجريبية',
            'experience_years' => 10,
            'license_number' => 'TEST-123',
            'is_active' => true,
            'is_verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'active'
        ]);
        
        // الحصول على معرف الخدمة التجريبية التي أنشأناها سابقًا
        $serviceId = DB::table('services')
            ->where('name', 'جلسة استشارية نفسية تجريبية')
            ->value('id');
        
        // ربط المتخصص بالخدمة التجريبية
        if ($serviceId) {
            DB::table('specialist_service')->insert([
                'specialist_id' => $specialistId,
                'service_id' => $serviceId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // الحصول على معرف الخدمة التجريبية
        $serviceId = DB::table('services')
            ->where('name', 'جلسة استشارية نفسية تجريبية')
            ->value('id');
            
        // الحصول على معرف المتخصص التجريبي
        $specialistId = DB::table('specialists')
            ->where('license_number', 'TEST-123')
            ->value('id');
        
        // إذا وجدنا المتخصص والخدمة، نحذف العلاقة بينهما
        if ($specialistId && $serviceId) {
            DB::table('specialist_service')
                ->where('specialist_id', $specialistId)
                ->where('service_id', $serviceId)
                ->delete();
        }
        
        // الحصول على معرف المستخدم المرتبط بالمتخصص
        if ($specialistId) {
            $userId = DB::table('specialists')
                ->where('id', $specialistId)
                ->value('user_id');
                
            // حذف المتخصص
            DB::table('specialists')
                ->where('id', $specialistId)
                ->delete();
            
            // حذف المستخدم المرتبط بالمتخصص إذا وجد
            if ($userId) {
                DB::table('users')
                    ->where('id', $userId)
                    ->delete();
            }
        }
    }
};
