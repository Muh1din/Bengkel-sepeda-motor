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
        Schema::create('vehicle_inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->unique();
            $table->unsignedBigInteger('mechanic_id');
            $table->enum('status', ['IN_PROGRESS','COMPLETED',])->default('IN_PROGRESS');
            $table->text('inspection')->nullable(true);
            $table->text('diagnosis')->nullable(true);
            $table->text('recommendation')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->foreign('booking_id')->on('bookings')->references('id');

            $table->foreign('mechanic_id')->on('users')->references('id');

            $table->foreign('created_by')->on('users')->references('id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_inspections');
    }
};
