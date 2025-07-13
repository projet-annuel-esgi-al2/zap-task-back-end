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
        Schema::create('workflow_actions_execution_history', function (Blueprint $table) {
            $table->uuid('id')
                ->default(new Expression('uuid_generate_v4()'))
                ->primary();
            $table->string('execution_status');
            $table->unsignedInteger('execution_order');
            $table->text('exception')
                ->nullable();
            $table->string('url')
                ->nullable();
            $table->jsonb('body_parameters')
                ->default('{}');
            $table->jsonb('url_parameters')
                ->default('{}');
            $table->jsonb('query_parameters')
                ->default('{}');
            $table->dateTime('executed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_actions_execution_history');
    }
};
