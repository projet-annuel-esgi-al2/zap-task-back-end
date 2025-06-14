<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();  // changer ici
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        \DB::table('services')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'google_calendar',
            'description' => 'Google Calendar service',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('services');
    }
};
