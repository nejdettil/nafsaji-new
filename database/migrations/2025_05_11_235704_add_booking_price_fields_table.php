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
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('duration')->default(60)->comment('Duration in minutes');
            $table->text('admin_notes')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded', 'partial'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'online'])->nullable();
            $table->string('transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'price',
                'duration',
                'admin_notes',
                'payment_status',
                'payment_method',
                'transaction_id'
            ]);
        });
    }
};
