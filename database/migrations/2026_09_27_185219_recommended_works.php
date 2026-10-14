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
        Schema::create('recommended_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_inspection_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->enum('approval_status', ['PENDING', 'ACCEPTED', 'REJECTED',])->default('PENDING');
            $table->foreign('vehicle_inspection_id')->on('vehicle_inspections')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommended_works');
    }
};
