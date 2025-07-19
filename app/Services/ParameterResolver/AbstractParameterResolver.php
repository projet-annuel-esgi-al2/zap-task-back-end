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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Uri;

abstract class AbstractParameterResolver
{
    protected array $values = [];

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

        $parameters = $this->getBodyParameters();

        return $this->resolveTemplate($this->getBodyTemplate(), self::getAfterResolutionParameters($parameters), self::getBeforeResolutionParameters($parameters));
    }

    public function resolveBodyParameters(): array
    {
        $parameters = $this->getBodyParameters();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters), self::getBeforeResolutionParameters($parameters));
    }

    public function resolveQueryParameters(): array
    {
        $parameters = $this->getQueryParameters();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters), self::getBeforeResolutionParameters($parameters));
    }

    public function resolveUrlParameters(): array
    {
        $parameters = $this->getUrlParameters();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters), self::getBeforeResolutionParameters($parameters));
    }

    public function resolveHeaders(): array
    {
        $parameters = $this->getHeaders();

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters), self::getBeforeResolutionParameters($parameters));
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

    protected function resolveTemplate(string $template, Collection $afterResolution, Collection $beforeResolution): array
    {
        $parameters = $this->fireBeforeResolutionHooks($this->getParameterValues(), $beforeResolution);

        $parameterValues = collect(['_r' => $this])
            ->merge($parameters)
            /** @phpstan-ignore-next-line */
            ->map(fn ($paramValue) => is_array($paramValue) ? new HtmlString(json_encode($paramValue)) : $paramValue)
            ->toArray();

        $template = \Str::of($template)
            ->replaceMatches('/"<<\s*(.*?)\s*>>"/', fn ($match) => '{{'.$match[1].'}}')
            ->toString();

        $resolvedParameters = Collection::fromJson(Blade::render($template, $parameterValues));

        return $this->fireAfterResolutionHooks($resolvedParameters, $afterResolution)->toArray();
    }

    protected function fireBeforeResolutionHooks(array $parameters, Collection $beforeResolution): Collection
    {
        return collect($parameters)->mapWithKeys(function ($parameterValue, $parameterKey) use ($beforeResolution) {
            if ($beforeResolution->hasAny($parameterKey)) {
                $beforeResolutionMethod = $beforeResolution[$parameterKey];

                return [$parameterKey => $this->{$beforeResolutionMethod}($parameterValue)];
            }

            return [$parameterKey => $parameterValue];
        });
    }

    protected function fireAfterResolutionHooks(Collection $resolvedParameters, Collection $afterResolution): Collection
    {
        return $resolvedParameters->mapWithKeys(function ($parameterValue, $parameterKey) use ($afterResolution) {
            if ($afterResolution->hasAny($parameterKey)) {
                $afterResolutionMethod = $afterResolution[$parameterKey];

                return [$parameterKey => $this->{$afterResolutionMethod}($parameterValue)];
            }

            return [$parameterKey => $parameterValue];
        });
    }

    protected function resolveParameters(string $parameters, Collection $afterResolution, Collection $beforeResolution): array
    {
        return $this->resolveTemplate($parameters, $afterResolution, $beforeResolution);
    }

    private static function getAfterResolutionParameters(array $parameters): Collection
    {
        return collect($parameters)
            ->filter(fn ($parameter) => ! is_null(Arr::get($parameter, 'afterResolutionHook')))
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['afterResolutionHook']]);
    }

    private static function getBeforeResolutionParameters(array $parameters): Collection
    {
        return collect($parameters)
            ->filter(fn ($parameter) => ! is_null(Arr::get($parameter, 'beforeResolutionHook')))
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['beforeResolutionHook']]);
    }
}
