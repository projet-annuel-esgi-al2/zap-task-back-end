<?php

namespace App\Models;

use App\Enums\WorkflowAction\Status;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Uri;

/**
 * @property Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkflowActionHistory> $history
 * @property-read int|null $history_count
 * @property-read \App\Models\WorkflowActionHistory|null $latestExecution
 * @property-read \App\Models\ServiceAction|null $serviceAction
 * @property-read \App\Models\Workflow|null $workflow
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

    public function url(): Attribute
    {
        return Attribute::make(
            set: function ($url) {
                $url = strtr($url, collect($this->url_parameters)->mapWithKeys(fn ($val, $key) => ['{'.$key.'}' => $val])->toArray());
                $uri = Uri::of($url);
                if (! empty($this->query_parameters)) {
                    $uri->withQuery($this->query_parameters);
                }

                return $uri->value();
            }
        );
    }

    public function getParametersForApi(): array
    {
        $workflowActionParameters = collect(array_merge($this->body_parameters, $this->query_parameters, $this->url_parameters));

        return collect($this->serviceAction->parameters_for_api)
            ->map(function ($param) use ($workflowActionParameters) {
                $param['parameter_value'] = $workflowActionParameters[$param['parameter_key']];

                return $param;
            })
            ->toArray();
    }

    public static function getParametersFromRequest(array $serviceActionParameters, array $parameters): array
    {
        return collect($parameters)
            ->filter(fn ($_, $parameterKey) => collect($serviceActionParameters)
                ->contains(fn ($param) => $parameterKey === Arr::get($param, 'parameter_key'))
            )
            ->toArray();
    }

    public static function fromApiRequest(array $actions): Collection
    {
        $serviceActions = ServiceAction::all();

        return collect($actions)
            ->map(function ($action) use ($serviceActions) {
                $serviceAction = $serviceActions->find($action['service_action_id']);

                $mergeData = [
                    'body_parameters' => self::getParametersFromRequest($serviceAction->body_parameters, $action['parameters']),
                    'query_parameters' => self::getParametersFromRequest($serviceAction->query_parameters, $action['parameters']),
                    'url_parameters' => self::getParametersFromRequest($serviceAction->url_parameters, $action['parameters']),
                    'headers' => self::getParametersFromRequest($serviceAction->headers, $action['parameters']),
                    'url' => $serviceAction->url,
                ];

                if (! isset($action['status'])) {
                    $mergeData['status'] = Status::Draft;
                }

                unset($action['parameters']);

                return array_merge($action, $mergeData);
            });
    }
}
