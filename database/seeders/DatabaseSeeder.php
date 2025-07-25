<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            ServiceScopeSeeder::class,
            ServiceActionSeeder::class,
        ]);
    }
}
