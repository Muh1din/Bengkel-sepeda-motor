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
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('work_order_id')
                ->nullable()
                ->after('material_id');

            $table->foreign('work_order_id')
                ->references('id')
                ->on('work_orders')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
            $table->dropColumn('work_order_id');
        });
    }
};
