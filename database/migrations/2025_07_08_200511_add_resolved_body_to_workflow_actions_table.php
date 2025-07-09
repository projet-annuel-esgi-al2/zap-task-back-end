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
        Schema::table('workflow_actions', function (Blueprint $table) {
            $table->jsonb('resolved_body')
                ->default('{}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_actions', function (Blueprint $table) {
            $table->dropColumn('resolved_body');
        });
    }
};
