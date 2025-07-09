<?php

namespace App\Services;

use App\Models\WorkflowAction;
use App\Traits\Makeable;
use Illuminate\Support\Facades\Blade;

class ParameterResolver
{
    use Makeable;

    public function __construct(protected WorkflowAction $workflowAction, protected array $values) {}

    public function resolve(): WorkflowAction
    {
        return $this->workflowAction->fill([
            'body_parameters' => $this->resolveBodyParameters(),
            'query_parameters' => $this->resolveQueryParameters(),
            'url_parameters' => $this->resolveUrlParameters(),
            'headers' => $this->resolveHeaders(),
            'resolved_body' => $this->resolveBody(),
        ]);
    }

    public function resolveBody(): array
    {
        if (empty($this->workflowAction->serviceAction->body_template)) {
            return $this->resolveBodyParameters();
        }

        return $this->resolveTemplate($this->workflowAction->serviceAction->body_template);
    }

    public function resolveBodyParameters(): array
    {
        return $this->resolveParameters($this->workflowAction->serviceAction->getRawOriginal('body_parameters'));
    }

    public function resolveQueryParameters(): array
    {
        return $this->resolveParameters($this->workflowAction->serviceAction->getRawOriginal('query_parameters'));
    }

    public function resolveUrlParameters(): array
    {
        $parameters = $this->resolveParameters($this->workflowAction->serviceAction->getRawOriginal('url_parameters'));

        return $parameters;
    }

    public function resolveHeaders(): array
    {
        return $this->resolveParameters($this->workflowAction->serviceAction->getRawOriginal('headers'));
    }

    private function resolveTemplate(string $template): array
    {
        $parameterValues = array_merge(
            ['_r' => $this],
            $this->values,
        );

        return json_decode(Blade::render($template, $parameterValues), true);
    }

    private function resolveParameters(string $parameters): array
    {
        $parameters = $this->resolveTemplate($parameters);

        return collect($parameters)
            ->mapWithKeys(fn ($parameter) => [$parameter['parameter_key'] => $parameter['parameter_value']])
            ->toArray();
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
        return app()->joinPaths(config('app.url'), '/api/workflows/trigger');
    }
}
