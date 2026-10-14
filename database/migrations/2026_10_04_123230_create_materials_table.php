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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_category_id');

                $table->string('title', 150);
                $table->string('slug', 180)->unique();
                $table->string('vehicle_name', 100);
                $table->string('summary', 255);
                $table->text('description')->nullable();
                $table->string('status', 20)->default('draft');
                $table->date('completed_at')->nullable();

                $table->foreign('project_category_id')
                    ->references('id')
                    ->on('project_categories')
                    ->onDelete('restrict');

                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
