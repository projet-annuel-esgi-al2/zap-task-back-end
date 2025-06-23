<?php

namespace Database\Seeders;

use App\Enums\ServiceEvent\Identifier;
use App\Enums\ServiceEvent\TriggerNotificationType;
use App\Enums\ServiceEvent\Type;
use App\Models\Service;
use App\Models\ServiceEvent;
use Database\Seeders\Traits\ReadsJsonFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ServiceEventSeeder extends Seeder
{
    use ReadsJsonFile;

    public function run(): void
    {
        $seedData = self::readDataFromFile();

        $seedData = collect(self::readDataFromFile());

        $serviceIdentifiers = Service::pluck('id', 'identifier');

        $seedData = $seedData
            ->map(function ($record) use ($serviceIdentifiers) {
                $serviceId = Arr::get($serviceIdentifiers, $record['service_identifier']);
                $areEnumValuesInvalid = is_null(Identifier::tryFrom($record['identifier']))
                    || is_null(Type::tryFrom($record['type']))
                    || is_null(TriggerNotificationType::tryFrom($record['trigger_notification_type']));

                if (is_null($serviceId) || $areEnumValuesInvalid) {
                    return [];
                }

                unset($record['service_identifier']);

                return array_merge($record, [
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })
            ->toArray();

        ServiceEvent::insert($seedData);

    }
}
