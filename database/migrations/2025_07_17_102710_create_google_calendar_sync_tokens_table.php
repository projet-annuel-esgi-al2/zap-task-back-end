<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_calendar_sync_tokens', function (Blueprint $table) {
            $table->uuid('id')
                ->default(new Expression('uuid_generate_v4()'))
                ->primary();
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('calendar_id');
            $table->string('sync_token')
                ->nullable();
            $table->dateTime('last_time_min_parameter')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_calendar_sync_tokens');
    }
};
