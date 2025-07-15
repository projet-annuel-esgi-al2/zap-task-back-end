<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\ParameterResolver\ServiceAction;

use App\Models\OAuthToken;
use App\Models\ServiceAction;
use App\Services\ParameterResolver\AbstractParameterResolver;
use App\Services\ParameterResolver\ServiceAction\Traits\HasGoogleCalendarDynamicParameters;
use App\Traits\Makeable;
use Illuminate\Support\Arr;

class ParameterResolver extends AbstractParameterResolver
{
    use HasGoogleCalendarDynamicParameters;
    use Makeable;

    public function __construct(protected ServiceAction $serviceAction, protected array $values = [], protected ?OAuthToken $oauthToken = null) {}

    public function oauthToken(): ?OAuthToken
    {
        return $this->oauthToken;
    }

    public function resolve(): ServiceAction
    {
        return $this->serviceAction->fill([
            'body_parameters' => self::mergeParameters(
                self::filterOutOptionParameters($this->serviceAction->body_parameters),
                $this->resolveBodyParameters(),
            ),
            'query_parameters' => self::mergeParameters(
                self::filterOutOptionParameters($this->serviceAction->query_parameters),
                $this->resolveQueryParameters(),
            ),
            'url_parameters' => self::mergeParameters(
                self::filterOutOptionParameters($this->serviceAction->url_parameters),
                $this->resolveUrlParameters(),
            ),
            'headers' => self::mergeParameters(
                self::filterOutOptionParameters($this->serviceAction->headers),
                $this->resolveHeaders(),
            ),
        ]);
    }

    public function getBodyTemplate(): ?string
    {
        return $this->serviceAction->body_template;
    }

    public function getBodyParameters(): array
    {
        return collect($this->serviceAction->body_parameters)
            ->filter(fn ($parameter) => Arr::get($parameter, 'options'))
            ->map(fn ($parameter) => Arr::except($parameter, 'parameter_value'))
            ->toArray();
    }

    public function getUrlParameters(): array
    {
        return collect($this->serviceAction->url_parameters)
            ->filter(fn ($parameter) => Arr::get($parameter, 'options'))
            ->map(fn ($parameter) => Arr::except($parameter, 'parameter_value'))
            ->toArray();
    }

    public function getQueryParameters(): array
    {
        return collect($this->serviceAction->query_parameters)
            ->filter(fn ($parameter) => Arr::get($parameter, 'options'))
            ->map(fn ($parameter) => Arr::except($parameter, 'parameter_value'))
            ->toArray();
    }

    public function getHeaders(): array
    {
        return collect($this->serviceAction->headers)
            ->filter(fn ($parameter) => Arr::get($parameter, 'options'))
            ->map(fn ($parameter) => Arr::except($parameter, 'parameter_value'))
            ->toArray();
    }

    public function getParameterValues(): array
    {
        return $this->values;
    }

    public function getUrl(): string
    {
        return $this->serviceAction->url;
    }

    private static function filterOutOptionParameters(array $parameters): array
    {
        return collect($parameters)
            ->filter(fn ($parameter) => Arr::get($parameter, 'options') === null)
            ->toArray();
    }

    private static function mergeParameters(array $initialParameters, array $resolvedParameters): array
    {
        return collect($initialParameters)
            ->filter(fn ($parameter) => Arr::get($parameter, 'options') === null)
            ->merge($resolvedParameters)
            ->toArray();
    }
}
