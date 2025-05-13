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
        // إضافة فئة خدمات جديدة للتجربة
        $categoryId = DB::table('service_categories')->insertGetId([
            'name' => 'الجلسات النفسية التجريبية',
            'description' => 'فئة الجلسات النفسية التجريبية لغرض الاختبار',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // إضافة خدمة جديدة مرتبطة بالفئة
        DB::table('services')->insert([
            'category_id' => $categoryId,
            'name' => 'جلسة استشارية نفسية تجريبية',
            'description' => 'جلسة استشارية نفسية فردية مدتها 60 دقيقة لمناقشة المشكلات النفسية والحصول على الدعم اللازم',
            'price' => 250.00,
            'duration' => 60,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف الخدمة التجريبية
        DB::table('services')->where('name', 'جلسة استشارية نفسية تجريبية')->delete();
        
        // حذف فئة الخدمة التجريبية
        DB::table('service_categories')->where('name', 'الجلسات النفسية التجريبية')->delete();
    }
};
