<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\ParameterResolver;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Uri;

abstract class AbstractParameterResolver
{
    abstract public function resolve(): Model;

    abstract public function getBodyTemplate(): ?string;

    abstract public function getBodyParameters(): array;

    abstract public function getUrlParameters(): array;

    abstract public function getQueryParameters(): array;

    abstract public function getHeaders(): array;

    abstract public function getParameterValues(): array;

    abstract public function getUrl(): string;

    public function resolveBody(): array
    {
        if (empty($this->getBodyTemplate())) {
            return $this->resolveBodyParameters();
        }

        return $this->resolveTemplate($this->getBodyTemplate(), self::getAfterResolutionParameters($this->getBodyParameters()));
    }

    public function resolveBodyParameters(): array
    {
        $parameters = $this->getBodyParameters();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveQueryParameters(): array
    {
        $parameters = $this->getQueryParameters();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveUrlParameters(): array
    {
        $parameters = $this->getUrlParameters();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveHeaders(): array
    {
        $parameters = $this->getHeaders();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveUrl(array $urlParameters, array $queryParameters): string
    {
        $url = $this->getUrl();

        if (empty($urlParameters)) {
            return $url;
        }

        $url = strtr($url, collect($urlParameters)->mapWithKeys(fn ($val, $key) => ['{'.$key.'}' => $val])->toArray());
        $uri = Uri::of($url);
        if (! empty($queryParameters)) {
            $uri->withQuery($queryParameters);
        }

        return $uri->value();
    }

    protected function resolveTemplate(string $template, Collection $afterResolution): array
    {
        $parameterValues = array_merge(
            ['_r' => $this],
            $this->getParameterValues(),
        );
        $template = \Str::of($template)
            ->replaceMatches('/"<<\s*(.*?)\s*>>"/', fn ($match) => '{{'.$match[1].'}}')
            ->toString();

        $resolvedParameters = Collection::fromJson(Blade::render($template, $parameterValues));

        return $resolvedParameters->mapWithKeys(function ($parameterValue, $parameterKey) use ($afterResolution) {
            if ($afterResolution->hasAny($parameterKey)) {
                $afterResolutionMethod = $afterResolution[$parameterKey];

                return [$parameterKey => $this->{$afterResolutionMethod}($parameterValue)];
            }

            return [$parameterKey => $parameterValue];
        })->toArray();
    }

    protected function resolveParameters(string $parameters, Collection $afterResolution): array
    {
        return $this->resolveTemplate($parameters, $afterResolution);
    }

    private static function getAfterResolutionParameters(array $parameters): Collection
    {
        return collect($parameters)
            ->filter(fn ($parameter) => ! is_null(Arr::get($parameter, 'afterResolutionHook')))
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['afterResolutionHook']]);
    }
}
