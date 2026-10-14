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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('user_id');

            $table->enum('type', [
                'IN',
                'OUT',
                'ADJUSTMENT'
            ]);

            $table->unsignedInteger('quantity');
            $table->unsignedInteger('stock_before');
            $table->unsignedInteger('stock_after');

            $table->text('notes')->nullable();

            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onDelete('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
