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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable(false)->unique();
            $table->unsignedBigInteger('customer_id')->nullable(false);
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();

           $table->foreign('booking_id')->on('bookings')->references('id');
           $table->foreign('customer_id')->on('customers')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
