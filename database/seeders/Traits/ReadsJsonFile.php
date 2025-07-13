<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace Database\Seeders\Traits;

use Illuminate\Support\Str;

trait ReadsJsonFile
{
    private static function defaultFileName(): string
    {
        return Str::of(static::class)
            ->afterLast('\\')
            ->before('Seeder')
            ->snake()
            ->append('_seeds.json')
            ->toString();
    }

    public static function readDataFromFile($fileName = null): array
    {
        $fileName ??= base_path(static::defaultFileName());

        return json_decode(file_get_contents($fileName), true);
    }
}
