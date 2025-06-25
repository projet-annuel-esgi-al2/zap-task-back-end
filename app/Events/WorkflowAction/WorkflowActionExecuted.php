<?php

namespace App\Events\WorkflowAction;

use App\Models\WorkflowAction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class WorkflowActionExecuted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Carbon $executedAt;

    public function __construct(
        public WorkflowAction $workflowAction,
        public readonly bool $success = true,
        public readonly ?string $exception = null,
        ?Carbon $executedAt = null,
    ) {
        $this->executedAt = $executedAt ?? now();
    }
}
