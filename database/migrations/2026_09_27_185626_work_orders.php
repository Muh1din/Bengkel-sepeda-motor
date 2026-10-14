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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->unique();
            $table->unsignedBigInteger('mechanic_id');
            $table->string('work_order_code')->unique();
            $table->dateTime('received_at');
            $table->enum('status', ['IN_PROGRESS', 'COMPLETED',])->default('IN_PROGRESS');
            $table->text('notes')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreign('booking_id')->on('bookings')->references('id');
            $table->foreign('mechanic_id')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
