<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Enums\Traits;

use BackedEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait EnumTrait
{
    public static function values($keyedByValue = false): array
    {
        return self::toCollection()->pluck('value', $keyedByValue ? 'value' : null)->toArray();
    }

    public static function snakeCaseValues($keyedByValue = false): array
    {
        return collect(self::values($keyedByValue))->mapWithKeys(fn ($value, $key) => [$key => Str::snake($value)])->toArray();
    }

    public static function options(): array
    {
        return self::toCollection()->pluck('name', 'value')->map(fn ($option) => Str::headline($option))->toArray();
    }

    public static function optionsWithTranslations(): array
    {
        return self::toCollection()->pluck('name', 'value')
            ->map(function ($option) {
                return self::translations()[$option] === '0' ? '0' : Str::headline(self::translations()[$option]);
            })->toArray();
    }

    public static function alphabeticallyOrderedOptionsWithTranslations(): array
    {
        return collect(self::optionsWithTranslations())->sort()->toArray();
    }

    public static function optionsValues(): array
    {
        return array_values(self::options());
    }

    public static function names(): array
    {
        return self::toCollection()->pluck('name')->toArray();
    }

    public static function implodedValues(string $separator = ','): string
    {
        return implode($separator, self::values());
    }

    public static function implodedSnakeCaseValues(string $separator = ','): string
    {
        return implode($separator, self::snakeCaseValues());
    }

    public static function toCollection(): Collection
    {
        return collect(self::cases());
    }

    public static function translations(?string $locale = null): array
    {
        return self::toCollection()
            ->flatMap(fn ($item) => [
                $item->name => __($item->value === 0 ? '0' : Str::headline($item->value), locale: $locale),
            ])->all();
    }

    public static function contains($value): bool
    {
        return in_array($value, self::values());
    }

    public static function optionsExcept($keys): array
    {
        return Arr::except(self::options(), self::prepareKeys($keys));
    }

    public static function optionsOnly($keys): array
    {
        return Arr::only(self::options(), self::prepareKeys($keys));
    }

    public static function valuesExcept($keys): array
    {
        return array_keys(self::optionsExcept($keys));
    }

    public static function valuesOnly($keys): array
    {
        return array_keys(self::optionsOnly($keys));
    }

    public static function optionsWithTranslationsOnly($keys): array
    {
        return Arr::only(self::optionsWithTranslations(), self::prepareKeys($keys));
    }

    public static function mirroredOptionsWithTranslationsOnly($keys): array
    {
        return array_flip(self::optionsWithTranslationsOnly($keys));
    }

    public static function optionsWithTranslationsExcept($keys): array
    {
        return Arr::except(self::optionsWithTranslations(), self::prepareKeys($keys));
    }

    public static function mirroredOptionsWithTranslationsExcept($keys): array
    {
        return array_flip(self::optionsWithTranslationsExcept($keys));
    }

    public static function implodedValuesExcept($keys, string $separator = ','): string
    {
        return implode($separator, self::valuesExcept($keys));
    }

    public static function implodedValuesOnly($keys, string $separator = ','): string
    {
        return implode($separator, self::valuesOnly($keys));
    }

    private static function prepareKeys($keys): array
    {
        $keys = is_array($keys) ? $keys : [$keys];

        return array_map(fn ($key) => $key instanceof BackedEnum ? $key->value : $key, $keys);
    }

    public static function mirroredOptions(): array
    {
        return array_flip(self::options());
    }

    public static function mirroredOptionsWithTranslations(): array
    {
        return array_flip(self::optionsWithTranslations());
    }

    public static function count(): int
    {
        return count(self::values());
    }

    public static function toJsArray(): array
    {
        return self::toCollection()->pluck('value', 'name')->toArray();
    }

    public static function randomValue()
    {
        return Arr::random(self::values());
    }

    public static function randomValueExcept($keys)
    {
        return Arr::random(self::valuesExcept($keys));
    }

    public static function randomCase()
    {
        return Arr::random(self::cases());
    }

    public static function optionsWithPrefixedTranslations(?string $locale = null)
    {
        return self::toCollection()->flatMap(fn ($item) => [$item->value => Str::headline(
            __(':'.self::class.'@'.$item->value, [
                self::class.'@'.$item->value => $item->value,
            ], locale: $locale)
        )])->all();
    }

    public static function optionsWithPrefixedTranslationsOnly($keys): array
    {
        return Arr::only(self::optionsWithPrefixedTranslations(), self::prepareKeys($keys));
    }

    public static function optionsWithPrefixedTranslationsExcept($keys): array
    {
        return Arr::except(self::optionsWithPrefixedTranslations(), self::prepareKeys($keys));
    }

    public function translation(?string $locale = null)
    {
        return static::translations($locale)[$this->name] ?? null;
    }

    public function prefixedTranslation(?string $locale = null)
    {
        return static::optionsWithPrefixedTranslations($locale)[$this->value] ?? null;
    }

    public static function tryFromCamelCase(string $value): ?static
    {
        return static::toCollection()->first(fn (BackedEnum $case) => \Str::camel($case->value) === $value);
    }

    public static function tryFromSnakeCase(?string $value): ?static
    {
        return static::toCollection()->first(fn (BackedEnum $case) => Str::snake($case->value) === $value);
    }

    public static function tryFromString(?string $value): ?static
    {
        return static::toCollection()->first(fn (BackedEnum $case) => Str::snake($case->value) === $value);
    }

    public static function tryFromTranslation(?string $value, ?string $locale = null): ?static
    {
        return static::tryFrom(with_locale($locale ?? app()->getLocale(), function () use ($value) {
            return collect(static::mirroredOptionsWithTranslations())
                ->first(fn ($item, $translation) => $translation === $value) ?? '';
        }));
    }

    public static function mirroredValues(): ?array
    {
        return array_mirror(static::values());
    }

    public function nameInHeadline(): string
    {
        return Str::headline($this->name);
    }

    public function valueInHeadline(): string
    {
        return Str::headline($this->value);
    }

    public function toSnakeCase(): string
    {
        return Str::snake($this->value);
    }

    public function toCamelCase(): string
    {
        return Str::camel($this->value);
    }
}
