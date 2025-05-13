<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // أولاً جعل حقل user_id اختياريًا (قد يكون null للزائرين)
            if (Schema::hasColumn('bookings', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
            
            // إضافة حقل guest_details لتخزين بيانات الزائرين بتنسيق JSON
            $table->json('guest_details')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // إزالة حقل guest_details
            $table->dropColumn('guest_details');
            
            // إعادة حقل user_id ليكون إلزاميًا
            if (Schema::hasColumn('bookings', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
        });
    }
};
