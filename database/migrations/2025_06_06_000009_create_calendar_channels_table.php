<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_channels', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID généré côté app
            $table->foreignUuid('user_service_id')->constrained()->onDelete('cascade');
            $table->string('calendar_id'); // ID Google Calendar (souvent une adresse mail)
            $table->string('resource_id'); // fourni par Google
            $table->string('channel_id');  // fourni par toi (UUID ou autre identifiant unique)
            $table->string('channel_token')->nullable(); // pour vérifier l’authenticité des notifs
            $table->timestamp('expiration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_channels');
    }
};
