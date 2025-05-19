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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quota_id')->nullable()->constrained()->onDelete('set null');
            $table->float('weight')->nullable();
            $table->integer('estimated_price')->nullable();
            $table->integer('final_price')->nullable();
            $table->text('pickup_address');
            $table->text('delivery_address')->nullable();
            $table->dateTime('pickup_date');
            $table->dateTime('delivery_date');
            $table->text('special_instructions')->nullable();
            $table->enum('status', [
                'pending',
                'collected',
                'in_transit',
                'washing',
                'ironing',
                'ready_for_delivery',
                'delivering',
                'delivered',
                'cancelled'
            ])->default('pending');
            $table->enum('payment_method', ['cash', 'mobile_money', 'quota'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
