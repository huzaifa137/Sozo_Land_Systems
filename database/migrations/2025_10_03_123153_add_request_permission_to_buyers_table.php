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
        // Guarded: production already has this column (it was added outside the migration set).
        if (Schema::hasColumn('buyers', 'request_permission')) {
            return;
        }

        Schema::table('buyers', function (Blueprint $table) {
            $table->integer('request_permission')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            //
        });
    }
};
