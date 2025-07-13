<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workflow_actions_execution_history', function (Blueprint $table) {
            $table->foreignUuid('workflow_action_id')
                ->constrained();
            $table->string('response_http_code');
            $table->dropColumn('body_parameters');
            $table->dropColumn('url_parameters');
            $table->dropColumn('query_parameters');
            $table->jsonb('parameters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_actions_execution_history', function (Blueprint $table) {
            $table->jsonb('body_parameters');
            $table->jsonb('url_parameters');
            $table->jsonb('query_parameters');
            $table->dropColumn('response_http_code');
            $table->dropForeign('workflow_action_id');
        });
    }
};
