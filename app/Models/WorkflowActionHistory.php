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
