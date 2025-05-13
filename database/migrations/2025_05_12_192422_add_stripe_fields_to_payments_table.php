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
        Schema::table('payments', function (Blueprint $table) {
            // حقول Stripe - نتحقق من وجودها أولاً
            if (!Schema::hasColumn('payments', 'stripe_payment_id')) {
                $table->string('stripe_payment_id')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('payments', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('stripe_payment_id');
            }
            
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('currency');
            }
            
            // حقول العلاقة متعددة الأشكال 
            if (!Schema::hasColumn('payments', 'payable_type')) {
                $table->string('payable_type')->nullable();
                $table->unsignedBigInteger('payable_id')->nullable();
                $table->index(['payable_type', 'payable_id']);
            }
            
            // معلومات إضافية
            if (!Schema::hasColumn('payments', 'metadata')) {
                $table->json('metadata')->nullable();
            }
            
            if (!Schema::hasColumn('payments', 'description')) {
                $table->text('description')->nullable();
            }
            
            if (!Schema::hasColumn('payments', 'receipt_url')) {
                $table->string('receipt_url')->nullable();
            }
            
            if (!Schema::hasColumn('payments', 'error_message')) {
                $table->text('error_message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // نحذف الحقول فقط إذا كانت موجودة
            $columns = [
                'stripe_payment_id',
                'stripe_customer_id',
                'metadata',
                'description',
                'receipt_url',
                'error_message'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
