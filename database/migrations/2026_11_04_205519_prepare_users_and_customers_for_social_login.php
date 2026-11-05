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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->string('password')->nullable()->change();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('name', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone', 20)->nullable(false)->change();
            $table->string('email', 50)->nullable()->change();
            $table->string('name', 100)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
        });
    }
};
