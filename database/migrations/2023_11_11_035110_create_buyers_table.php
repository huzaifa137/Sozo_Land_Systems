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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->text('firstname');
            $table->text('lastname');
            $table->text('gender');
            $table->text('date_of_birth');
            $table->text('NIN');
            $table->text('national_id');
            $table->text('card_number');
            $table->text('land_poster');
            $table->text('method_payment');
            $table->text('purchase_type');
            $table->text('estate');
            $table->text('location');
            $table->text('width');
            $table->text('height');
            $table->text('plot_number');
            $table->text('amount_payed');
            $table->text('balance');
            $table->text('next_installment_pay');
            $table->text('reciepts')->default('Pending');
            $table->text('agreement')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
