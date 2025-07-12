<?php

namespace App\Services;

use App\Models\WorkflowAction;
use App\Traits\Makeable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;

class ParameterResolver
{
    use Makeable;

    public function __construct(protected WorkflowAction $workflowAction, protected array $values) {}

    public function resolve(): WorkflowAction
    {
        $resolvedQueryParameters = $this->resolveQueryParameters();
        $resolvedUrlParameters = $this->resolveUrlParameters();

        return $this->workflowAction->fill([
            'body_parameters' => $this->resolveBodyParameters(),
            'query_parameters' => $resolvedQueryParameters,
            'url_parameters' => $resolvedUrlParameters,
            'headers' => $this->resolveHeaders(),
            'resolved_body' => $this->resolveBody(),
            'url' => $this->resolveUrl($resolvedUrlParameters, $resolvedQueryParameters),
        ]);
    }

    public function resolveBody(): array
    {
        if (empty($this->workflowAction->serviceAction->body_template)) {
            return $this->resolveBodyParameters();
        }

        return $this->resolveTemplate($this->workflowAction->serviceAction->body_template, self::getAfterResolutionParameters($this->workflowAction->serviceAction->body_parameters));
    }

    public function resolveBodyParameters(): array
    {
        $parameters = $this->workflowAction->serviceAction->body_parameters;

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveQueryParameters(): array
    {
        $parameters = $this->workflowAction->serviceAction->query_parameters;

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveUrlParameters(): array
    {
        $parameters = $this->workflowAction->serviceAction->url_parameters;

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveHeaders(): array
    {
        $parameters = $this->workflowAction->serviceAction->headers;

        return $this->resolveParameters(json_encode($parameters), self::getAfterResolutionParameters($parameters));
    }

    public function resolveUrl(array $urlParameters, array $queryParameters): string
    {
        $url = $this->workflowAction->serviceAction->url;

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

    private function resolveTemplate(string $template, Collection $afterResolution): array
    {
        $parameterValues = array_merge(
            ['_r' => $this],
            $this->values,
        );

        $resolvedParameters = Collection::fromJson(Blade::render($template, $parameterValues));

        return $resolvedParameters->mapWithKeys(function ($parameterValue, $parameterKey) use ($afterResolution) {
            if ($afterResolution->hasAny($parameterKey)) {
                $afterResolutionMethod = $afterResolution[$parameterKey];

                return [$parameterKey => $this->{$afterResolutionMethod}($parameterValue)];
            }

            return [$parameterKey => $parameterValue];
        })->toArray();
    }

    private function resolveParameters(string $parameters, Collection $afterResolution): array
    {
        $parameters = $this->resolveTemplate($parameters, $afterResolution);

        return collect($parameters)
            ->filter(fn ($parameter) => ! empty($parameter['parameter_value']))
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['parameter_value']])
            ->toArray();
    }

    private static function getAfterResolutionParameters(array $parameters): Collection
    {
        return collect($parameters)
            ->filter(fn ($parameter) => ! is_null(Arr::get($parameter, 'afterResolutionHook')))
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['afterResolutionHook']]);
    }

    public function encodeBase64Url($value): string
    {
        return Str::of($value)
            ->toBase64()
            ->replace(['+', '/'], ['-', '_'])
            ->rtrim('=')
            ->value();
    }

    /*
     * Used when webhook subscription is done via a "channel" that the service requires to be identified
     * */
    public function channelId(): string
    {
        return $this->workflowAction->id;
    }

    /*
     * Used when service sends an ID with each webhook call
     * This token will be used to identify the workflow to be executed upon webhook call reception
     * */
    public function webhookToken(): string
    {
        return $this->workflowAction->workflow->id;
    }

    /*
     * Specifies a webhook address for the service to send a request to
     * */
    public function webhookAddress(): string
    {
        return Uri::of(config('app.url'))
            ->withPath('/api/workflows/trigger')
            ->withQuery([
                'w' => $this->workflowAction->workflow->id,
            ]);
    }
}
