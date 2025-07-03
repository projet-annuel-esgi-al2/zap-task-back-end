<?php

namespace App\Services;

use App\Models\WorkflowAction;
use App\Traits\Makeable;
use Illuminate\Support\Arr;
use ReflectionClass;

class ParameterResolver
{
    use Makeable;

    public function __construct(protected WorkflowAction $workflowAction) {}

    public function resolveBodyParameters(): array
    {
        return $this->resolveParameters($this->workflowAction->serviceAction->body_parameters);
    }

    public function resolveQueryParameters(): array
    {
        return $this->resolveParameters($this->workflowAction->serviceAction->query_parameters);
    }

    public function resolveUrlParameters(): array
    {
        return $this->resolveParameters($this->workflowAction->serviceAction->url_parameters);
    }

    private function resolveParameters(array $parameters): array
    {
        $reflection = new ReflectionClass(self::class);

        return collect($parameters)
            ->filter(fn ($parameter) => Arr::get($parameter, 'hidden') === true)
            ->flatMap(function ($parameter) use ($reflection) {
                $resolverMethod = Arr::get($parameter, 'parameter_value');
                if ($reflection->hasMethod($resolverMethod)) {
                    $parameter['parameter_value'] = $this->{$resolverMethod}();
                }
                $parameter[$parameter['parameter_key']] = $parameter['parameter_value'];

                unset($parameter['parameter_key']);
                unset($parameter['parameter_value']);
                unset($parameter['hidden']);

                return $parameter;
            })
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
