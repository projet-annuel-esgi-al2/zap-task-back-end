<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\ParameterResolver\WorkflowAction;

use App\Models\OAuthToken;
use App\Models\WorkflowAction;
use App\Services\ParameterResolver\AbstractParameterResolver;
use App\Services\ParameterResolver\ServiceAction\Traits\HasGoogleCalendarDynamicParameters;
use App\Services\ParameterResolver\WorkflowAction\Traits\HasGMailDynamicParameters;
use App\Services\ParameterResolver\WorkflowAction\Traits\HasGoogleDynamicParameters;
use App\Services\ParameterResolver\WorkflowAction\Traits\HasGoogleSheetsDynamicParameters;
use App\Traits\Makeable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Uri;

class ParameterResolver extends AbstractParameterResolver
{
    use HasGMailDynamicParameters;
    use HasGoogleCalendarDynamicParameters;
    use HasGoogleDynamicParameters;
    use HasGoogleSheetsDynamicParameters;
    use Makeable;

    public function __construct(protected WorkflowAction $workflowAction, protected array $values = []) {}

    public function oauthToken(): ?OAuthToken
    {
        return null;
    }

    public function workflowAction(): WorkflowAction
    {
        return $this->workflowAction;
    }

    public function getBodyTemplate(): ?string
    {
        return $this->workflowAction->serviceAction->body_template;
    }

    public function getBodyParameters(): array
    {
        return collect($this->workflowAction->serviceAction->body_parameters)
            ->map(fn ($parameter) => Arr::except($parameter, 'options'))
            ->toArray();
    }

    public function getUrlParameters(): array
    {
        return collect($this->workflowAction->serviceAction->url_parameters)
            ->map(fn ($parameter) => Arr::except($parameter, 'options'))
            ->toArray();
    }

    public function getQueryParameters(): array
    {
        return collect($this->workflowAction->serviceAction->query_parameters)
            ->map(fn ($parameter) => Arr::except($parameter, 'options'))
            ->toArray();
    }

    public function getHeaders(): array
    {
        return collect($this->workflowAction->serviceAction->headers)
            ->map(fn ($parameter) => Arr::except($parameter, 'options'))
            ->toArray();
    }

    public function getParameterValues(): array
    {
        return $this->values;
    }

    public function getUrl(): string
    {
        return $this->workflowAction->serviceAction->url;
    }

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

    protected function resolveParameters(string $parameters, Collection $afterResolution, Collection $beforeResolution): array
    {
        $resolvedParameters = collect(parent::resolveParameters($parameters, $afterResolution, $beforeResolution))
            ->filter(fn ($parameter) => ! empty($parameter['parameter_value']))
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['parameter_value']]);

        return $this->fireAfterResolutionHooks($resolvedParameters, $afterResolution)
            ->toArray();
    }

    /*
     * Specifies a webhook address for the service to send a request to
     * */
    public function webhookAddress(): HtmlString
    {
        return new HtmlString(Uri::of(config('app.url'))
            ->withPath('/api/trigger-workflow')
            ->withQuery([
                'w' => $this->workflowAction->workflow->id,
                'd' => $this->workflowAction->workflow->deployment_id,
            ])
            ->value());
    }

    public function formatToRfc3339String(string|array $date): string
    {
        return Carbon::parse($date)->toRfc3339String();
    }
}
