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
        Schema::create('service_actions', function (Blueprint $table) {
            $table->uuid('id')
                ->default(new Expression('uuid_generate_v4()'))
                ->primary();
            $table->string('name');
            $table->string('identifier');
            $table->foreignUuid('service_id')
                ->constrained();
            $table->string('type'); // trigger or action
            $table->string('url')
                ->nullable();
            $table->jsonb('body_parameters')
                ->default('{}');
            $table->jsonb('url_parameters')
                ->default('{}');
            $table->jsonb('query_parameters')
                ->default('{}');
            $table->string('trigger_notification_type')
                ->nullable(); // polling or webhook
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_actions');
    }
};
