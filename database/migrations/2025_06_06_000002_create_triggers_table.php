<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('triggers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->json('config_schema')->nullable(); // si chaque trigger a des paramètres
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('triggers');
    }
};
