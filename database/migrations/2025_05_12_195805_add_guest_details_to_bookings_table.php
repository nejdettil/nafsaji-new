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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('name')->nullable(); // اسم العميل
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->boolean('is_guest')->default(false); // هل هو زائر غير مسجل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'phone', 'is_guest']);
        });
    }
};
