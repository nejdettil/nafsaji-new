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
        // الحصول على معرف المتخصص التجريبي
        $specialistId = DB::table('specialists')
            ->where('license_number', 'TEST-123')
            ->value('id');
        
        if ($specialistId) {
            // إنشاء جدول مواعيد متاحة للمتخصص التجريبي
            // إنشاء جدول قياسي للأخصائي: الأحد إلى الخميس من 9 صباحاً إلى 5 مساءً
            $availability = [
                // الأحد (0) - من 9 صباحاً إلى 5 مساءً
                ['day' => 0, 'start' => '09:00:00', 'end' => '17:00:00'],
                // الإثنين (1) - من 9 صباحاً إلى 5 مساءً
                ['day' => 1, 'start' => '09:00:00', 'end' => '17:00:00'],
                // الثلاثاء (2) - من 9 صباحاً إلى 5 مساءً
                ['day' => 2, 'start' => '09:00:00', 'end' => '17:00:00'],
                // الأربعاء (3) - من 9 صباحاً إلى 5 مساءً
                ['day' => 3, 'start' => '09:00:00', 'end' => '17:00:00'],
                // الخميس (4) - من 9 صباحاً إلى 5 مساءً
                ['day' => 4, 'start' => '09:00:00', 'end' => '17:00:00'],
            ];
            
            // تحديث حقل المواعيد المتاحة للمتخصص
            DB::table('specialists')
                ->where('id', $specialistId)
                ->update([
                    'availability' => json_encode($availability)
                ]);
                
            // إنشاء جدول وقت تفصيلي للمتخصص في جدول SpecialistSchedule إذا كان موجوداً
            if (Schema::hasTable('specialist_schedules')) {
                for ($day = 0; $day < 5; $day++) {
                    DB::table('specialist_schedules')->insert([
                        'specialist_id' => $specialistId,
                        'day_of_week' => $day,
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'is_available' => true,
                        'created_at' => now(),
                        'updated_at' => now()
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
        // الحصول على معرف المتخصص التجريبي
        $specialistId = DB::table('specialists')
            ->where('license_number', 'TEST-123')
            ->value('id');
            
        if ($specialistId) {
            // إزالة جدول المواعيد المتاحة للمتخصص
            DB::table('specialists')
                ->where('id', $specialistId)
                ->update([
                    'availability' => null
                ]);
                
            // حذف جدول الوقت التفصيلي إذا كان موجوداً
            if (Schema::hasTable('specialist_schedules')) {
                DB::table('specialist_schedules')
                    ->where('specialist_id', $specialistId)
                    ->delete();
            }
        }
    }
};
