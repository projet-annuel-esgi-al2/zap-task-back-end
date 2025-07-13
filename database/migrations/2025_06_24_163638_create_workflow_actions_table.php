<?php

/*
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
        Schema::create('workflow_actions', function (Blueprint $table) {
            $table->uuid('id')
                ->default(new Expression('uuid_generate_v4()'))
                ->primary();
            $table->foreignUuid('workflow_id')
                ->constrained();
            $table->foreignUuid('service_action_id')
                ->constrained();
            $table->string('status');
            $table->unsignedInteger('execution_order');
            $table->string('url')
                ->nullable();
            $table->jsonb('body_parameters')
                ->default('{}');
            $table->jsonb('url_parameters')
                ->default('{}');
            $table->jsonb('query_parameters')
                ->default('{}');
            $table->dateTime('last_executed_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_actions');
    }
};
