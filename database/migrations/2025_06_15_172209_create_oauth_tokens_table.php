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
        Schema::create('oauth_tokens', function (Blueprint $table) {
            $table->uuid('id')
                ->default(new Expression('uuid_generate_v4()'))
                ->primary();
            $table->foreignUuid('user_id')
                ->constrained();
            $table->string('value');
            $table->string('refresh_token')
                ->nullable();
            $table->dateTime('expires_at')
                ->nullable();
            $table->foreignUuid('parent_token_id')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_tokens');
    }
};
