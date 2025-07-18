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
        Schema::table('workflow_actions_execution_history', function (Blueprint $table) {
            $table->uuid('deployment_id')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_actions_execution_history', function (Blueprint $table) {
            $table->dropColumn('deployment_id');
        });
    }
};
