<?php

namespace App\Models;

use App\Enums\WorkflowAction\Status;
use App\Models\Traits\HasHttpParameters;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
 *
 * @mixin \Eloquent
 */
class WorkflowAction extends Model
{
    use HasFactory;
    use HasHttpParameters;
    use HasUUID;

    protected $fillable = [
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
}
