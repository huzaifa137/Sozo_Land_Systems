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
        Schema::create('house_buyers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('NIN')->unique();
            $table->string('card_number')->unique();
            $table->string('phonenumber')->unique();
            $table->string('national_id_front');
            $table->string('national_id_back');
            $table->integer('house_id');
            $table->string('profile_pic');
            $table->integer('sold_by')->nullable();
            $table->integer('selling_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_buyers');
    }
};
