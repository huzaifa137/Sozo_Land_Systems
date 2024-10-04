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
        Schema::table('resales', function (Blueprint $table) {
           $table->text('amount_to_be_sold')->nullable();
           $table->text('seller_agreeement')->nullable();
           $table->integer('paid_cash')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resales', function (Blueprint $table) {
            //
        });
    }
};
