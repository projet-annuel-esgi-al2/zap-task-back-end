<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceScope;
use Database\Seeders\Traits\ReadsJsonFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ServiceScopeSeeder extends Seeder
{
    use ReadsJsonFile;

    public function run(): void
    {
        $seedData = collect(self::readDataFromFile());

        $serviceIdentifiers = Service::pluck('id', 'identifier');

        $seedData = $seedData
            ->map(function ($record) use ($serviceIdentifiers) {
                $serviceId = Arr::get($serviceIdentifiers, $record['identifier']);

                if (is_null($serviceId)) {
                    return [];
                }

                unset($record['identifier']);

                return array_merge($record, [
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })
            ->toArray();

        ServiceScope::insert($seedData);
    }
}
