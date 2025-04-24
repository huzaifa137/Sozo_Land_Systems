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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->text('price');
            $table->text('location');
            $table->text('width1');
            $table->text('width2');
            $table->text('height1');
            $table->text('height2');
            $table->text('LandTenure');
            $table->text('bedroom');
            $table->text('purchase_procedure');
            $table->text('amenities');
            $table->json('agreement_files')->nullable();
            $table->json('house_images')->nullable();
            $table->text('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
