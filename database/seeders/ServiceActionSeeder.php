<?php

namespace Database\Seeders;

use App\Enums\ServiceAction\Identifier;
use App\Enums\ServiceAction\TriggerNotificationType;
use App\Enums\ServiceAction\Type;
use App\Models\Service;
use App\Models\ServiceAction;
use Database\Seeders\Traits\ReadsJsonFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ServiceActionSeeder extends Seeder
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
                    'body_parameters' => json_encode($record['body_parameters']),
                    'url_parameters' => json_encode($record['url_parameters']),
                    'query_parameters' => json_encode($record['query_parameters']),
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })
            ->toArray();

        ServiceAction::insert($seedData);
    }
}
