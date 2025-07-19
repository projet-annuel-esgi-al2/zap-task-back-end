<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Models;

use App\Enums\WorkflowAction\Status;
use App\Models\Traits\HasUUID;
use App\Services\ParameterResolver\ServiceAction\ParameterResolver as ServiceActionParameterResolver;
use App\Services\ParameterResolver\WorkflowAction\ParameterResolver as WorkflowActionParameterResolver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;

/**
 * @property Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkflowActionHistory> $history
 * @property-read int|null $history_count
 * @property-read \App\Models\WorkflowActionHistory|null $latestExecution
 * @property-read \App\Models\ServiceAction|null $serviceAction
 * @property-read \App\Models\Workflow|null $workflow
 * @property-read array<array-key, mixed> $parameters
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction query()
 *
 * @property string $id
 * @property string $workflow_id
 * @property string $service_action_id
 * @property int $execution_order
 * @property string|null $url
 * @property array<array-key, mixed> $body_parameters
 * @property array<array-key, mixed> $url_parameters
 * @property array<array-key, mixed> $query_parameters
 * @property \Illuminate\Support\Carbon|null $last_executed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereBodyParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereExecutionOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereLastExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereQueryParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereServiceActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereUrlParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereWorkflowId($value)
 * @method static \Database\Factories\WorkflowActionFactory factory($count = null, $state = [])
 *
 * @property array $headers
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereHeaders($value)
 *
 * @property array $resolved_body
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereResolvedBody($value)
 *
 * @property Method $http_method
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowAction whereHttpMethod($value)
 *
 * @mixin \Eloquent
 */
class WorkflowAction extends Model
{
    use HasFactory;
    use HasUUID;

    protected $fillable = [
        'id',
        'workflow_id',
        'service_action_id',
        'status',
        'execution_order',
        'url',
        'body_parameters',
        'url_parameters',
        'query_parameters',
        'headers',
        'resolved_body',
        'http_method',
        'last_executed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'body_parameters' => 'array',
            'url_parameters' => 'array',
            'query_parameters' => 'array',
            'headers' => 'array',
            'resolved_body' => 'array',
            'http_method' => Method::class,
            'last_executed_at' => 'datetime',
        ];
    }

    public function serviceAction(): BelongsTo
    {
        return $this->belongsTo(ServiceAction::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(WorkflowActionHistory::class);
    }

    public function latestExecution(): HasOne
    {
        return $this->history()
            ->latest()
            ->one();
    }

    public function parameters(): Attribute
    {
        return Attribute::make(
            get: fn () => array_merge($this->body_parameters, $this->query_parameters, $this->url_parameters),
        );
    }

    public function getParametersForApi(): array
    {
        $workflowActionParameters = collect($this->parameters);
        $user = $this->workflow->user;
        /** @var ?\App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = $user->serviceSubscriptions()
            ->where('service_id', $this->serviceAction->service->id)
            ->first();
        $oauthToken = $serviceSubscription?->oauthToken;

        $resolvedServiceAction = $this->serviceAction;
        if (! is_null($oauthToken)) {
            $resolvedServiceAction = ServiceActionParameterResolver::make($resolvedServiceAction, oauthToken: $oauthToken)
                ->resolve();
        }

        return collect($resolvedServiceAction->parameters_for_api)
            ->map(function ($param) use ($workflowActionParameters) {
                if (isset($param['options'])) {
                    $workflowActionParameters['options'] = $param['options'];
                }

                if (Arr::get($param, 'parameter_key') === 'attendees') {
                    $emails = collect($param['parameter_value'])->pluck('email')->values()->toArray();
                    Arr::set($param, 'parameter_value', $emails);

                    return $param;
                }

                $param['parameter_value'] = $workflowActionParameters[$param['parameter_key']];

                return $param;
            })
            ->toArray();
    }

    /**
     * @return Collection<WorkflowAction>
     * */
    public static function createOrUpdateFromApiRequest(array $requestActionsData): Collection
    {
        return collect($requestActionsData)
            ->map(function ($actionData) {
                $serviceAction = ServiceAction::find($actionData['service_action_id']);

                $id = empty($actionData['id']) ? [] : ['id' => $actionData['id']];
                $actionModel = self::createOrUpdate(
                    uniqueBy: $id,
                    attributes: [
                        'service_action_id' => $actionData['service_action_id'],
                        'execution_order' => $actionData['execution_order'],
                    ],
                    onCreateOnly: [
                        'workflow_id' => $actionData['workflow_id'],
                        'http_method' => $serviceAction->http_method,
                        'status' => Status::Draft,
                    ]
                );

                $actionModelWithResolvedParameters = WorkflowActionParameterResolver::make($actionModel, $actionData['parameters'])
                    ->resolve();

                $actionModelWithResolvedParameters->save();

                return $actionModelWithResolvedParameters;
            });
    }

    public static function createOrUpdate(array $uniqueBy = [], array $attributes = [], array $onCreateOnly = []): self
    {
        $model = empty($uniqueBy)
            ? new self(array_merge($attributes, $onCreateOnly))
            : self::firstOrNew($uniqueBy, $attributes);
        $fillableAttributes = $model->only($model->getFillable());

        $updatedAttributes = array_filter(array_merge(
            $fillableAttributes,
            $attributes,
        ));
        $model->fill($updatedAttributes);

        if ($model->isDirty()) {
            $model->save();
        }

        return $model;
    }

    public function setAsTested(): self
    {
        $this->update([
            'status' => Status::Tested,
        ]);

        return $this;
    }

    public function setAsError(): self
    {
        $this->update([
            'status' => Status::Error,
        ]);

        return $this;
    }
}
