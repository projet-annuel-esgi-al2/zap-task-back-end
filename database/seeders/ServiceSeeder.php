<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Seeders;

use App\Models\Service;
use Database\Seeders\Traits\ReadsJsonFile;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    use ReadsJsonFile;

    public function run(): void
    {
        $seedData = self::readDataFromFile();

        $seedData = collect($seedData)
            ->map(fn ($record) => array_merge($record, [
                'created_at' => now(),
                'updated_at' => now(),
                'oauth_token_options' => json_encode($record['oauth_token_options']),
            ]))
            ->toArray();

        Service::insert($seedData);
    }
}
