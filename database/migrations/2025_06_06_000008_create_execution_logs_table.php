<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('execution_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('execution_id')->constrained()->onDelete('cascade');
            $table->string('step_name');
            $table->enum('step_type', ['trigger', 'action']);
            $table->json('input_data')->nullable();
            $table->json('output_data')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('execution_logs');
    }
};
