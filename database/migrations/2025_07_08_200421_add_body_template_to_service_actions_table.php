<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

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
        Schema::table('service_actions', function (Blueprint $table) {
            $table->text('body_template')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_actions', function (Blueprint $table) {
            $table->dropColumn('body_template');
        });
    }
};
