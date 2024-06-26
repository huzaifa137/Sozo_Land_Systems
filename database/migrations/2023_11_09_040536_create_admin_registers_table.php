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
        Schema::create('admin_registers', function (Blueprint $table) {
            $table->id();
            $table->text('username');
            $table->text('firstname');
            $table->text('lastname');
            $table->text('email');
            $table->text('password');
            $table->text('admin_category');
            $table->text('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_registers');
    }
};
