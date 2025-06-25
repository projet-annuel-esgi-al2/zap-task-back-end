<?php

namespace App\Models;

use App\Enums\WorkflowActionHistory\ExecutionStatus;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ExecutionStatus $execution_status
 * @property-read \App\Models\WorkflowAction|null $workflowAction
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory query()
 *
 * @property string $id
 * @property int $execution_order
 * @property string|null $exception
 * @property string|null $url
 * @property array<array-key, mixed> $body_parameters
 * @property array<array-key, mixed> $url_parameters
 * @property array<array-key, mixed> $query_parameters
 * @property \Illuminate\Support\Carbon $executed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereBodyParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereExecutionOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereExecutionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereQueryParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereUrlParameters($value)
 *
 * @mixin \Eloquent
 */
class WorkflowActionHistory extends Model
{
    use HasUUID;

    protected $table = 'workflow_actions_execution_history';

    protected $fillable = [
        'execution_status',
        'execution_order',
        'exception',
        'body_parameters',
        'url_parameters',
        'query_parameters',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'execution_status' => ExecutionStatus::class,
            'body_parameters' => 'array',
            'url_parameters' => 'array',
            'query_parameters' => 'array',
            'executed_at' => 'datetime',
        ];
    }

    public function workflowAction(): BelongsTo
    {
        return $this->belongsTo(WorkflowAction::class);
    }
}
