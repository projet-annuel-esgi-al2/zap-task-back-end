<?php

namespace App\Models;

use App\Enums\WorkflowActionHistory\ExecutionStatus;
use App\Models\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property string $response_http_code
 * @property \Illuminate\Support\Carbon $executed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereExecutionOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereExecutionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereUrl($value)
 *
 * @property string $workflow_action_id
 * @property array<array-key, mixed> $parameters
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereWorkflowActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkflowActionHistory whereResponseHttpCode($value)
 * @method static \Database\Factories\WorkflowActionHistoryFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class WorkflowActionHistory extends Model
{
    use HasFactory;
    use HasUUID;

    protected $table = 'workflow_actions_execution_history';

    protected $fillable = [
        'workflow_action_id',
        'response_http_code',
        'execution_status',
        'execution_order',
        'exception',
        'parameters',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'execution_status' => ExecutionStatus::class,
            'parameters' => 'array',
            'executed_at' => 'datetime',
        ];
    }

    public function workflowAction(): BelongsTo
    {
        return $this->belongsTo(WorkflowAction::class);
    }
}
