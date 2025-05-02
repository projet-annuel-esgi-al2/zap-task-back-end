<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
            DB::statement('ALTER TABLE users ALTER COLUMN id DROP DEFAULT;');
            DB::statement('ALTER TABLE users ALTER COLUMN id TYPE integer USING (id::integer);');
            DB::statement('ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval(\'users_id_seq\');');
        });
    }
};
