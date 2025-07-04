<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasHttpParameters
{
    private static function prepareParametersForApi(array $parameters): array
    {
        return collect($parameters)
            ->filter(fn (array $parameter) => $parameter['hidden'] === false)
            ->map(function (array $parameter) {
                unset($parameter['hidden']);

                return $parameter;
            })
            ->toArray();
    }

    protected function bodyParametersForApi(): Attribute
    {
        return Attribute::make(
            get: fn () => self::prepareParametersForApi($this->body_parameters), // @phpstan-ignore-line
        );
    }

    protected function queryParametersForApi(): Attribute
    {
        return Attribute::make(
            get: fn () => self::prepareParametersForApi($this->query_parameters), // @phpstan-ignore-line
        );
    }

    protected function urlParametersForApi(): Attribute
    {
        return Attribute::make(
            get: fn () => self::prepareParametersForApi($this->url_parameters), // @phpstan-ignore-line
        );
    }

    protected function parametersForApi(): Attribute
    {
        return Attribute::make(
            get : fn () => array_merge($this->body_parameters_for_api, $this->query_parameters_for_api, $this->url_parameters_for_api), // @phpstan-ignore-line
        );
    }
}
