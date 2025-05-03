<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE EXTENSION if NOT EXISTS "uuid-ossp"');

        DB::statement('ALTER TABLE users ALTER COLUMN id DROP DEFAULT;');
        DB::statement('ALTER TABLE users ALTER COLUMN id TYPE uuid USING (uuid_generate_v4());');
        DB::statement('ALTER TABLE users ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'uuid');
            $table->dropPrimary('users_pkey');
            $table->id();
        });

        DB::statement('ALTER TABLE users ALTER COLUMN uuid DROP DEFAULT;');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        DB::statement('DROP EXTENSION IF EXISTS "uuid-ossp";');
    }
};
