<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')
                ->default(new Expression('uuid_generate_v4()'))
                ->primary();
            $table->string('identifier');
            $table->string('name');
            $table->string('socialite_driver_identifier')
                ->nullable();
            $table->jsonb('oauth_token_options')
                ->default('{}')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
