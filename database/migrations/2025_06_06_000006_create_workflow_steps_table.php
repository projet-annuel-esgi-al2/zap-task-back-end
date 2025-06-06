<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workflow_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['trigger', 'action']);
            $table->unsignedBigInteger('ref_id'); // ID vers triggers/actions
            $table->json('config')->nullable();
            $table->unsignedInteger('order');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('workflow_steps');
    }
};
