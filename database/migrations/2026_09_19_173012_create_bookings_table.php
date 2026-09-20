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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('customer_id')->nullable(false);
            $table->unsignedBigInteger('vehicle_id')->nullable(false);
            $table->string('service_type');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('complaint')->nullable();

            $table->enum(
                'status',
                [
                    'PENDING',
                    'CONFIRMED',
                    'IN_PROGRESS',
                    'COMPLETED',
                    'REJECTED',
                ]
            )->default('PENDING');
            $table->timestamps();
            $table->foreign('customer_id')->on('customers')->references('id');
            $table->foreign('vehicle_id')->on('vehicles')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
