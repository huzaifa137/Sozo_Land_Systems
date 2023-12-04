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
        Schema::create('resales', function (Blueprint $table) {
            $table->id();
            $table->text('user_id');
            $table->text('purchase_type');
            $table->text('estate');
            $table->text('plot_number');
            $table->text('amount_resold');
            $table->text('reciept_resold');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resales');
    }
};
