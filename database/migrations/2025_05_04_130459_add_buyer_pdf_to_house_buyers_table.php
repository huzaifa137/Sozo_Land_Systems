<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('house_buyers', function (Blueprint $table) {
            $table->string('buyer_pdf')->nullable();
        });
    }

    public function down()
    {
        Schema::table('house_buyers', function (Blueprint $table) {
            $table->dropColumn('buyer_pdf');
        });
    }

};
