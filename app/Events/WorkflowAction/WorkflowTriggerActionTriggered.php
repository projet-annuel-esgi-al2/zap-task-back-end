<?php

namespace App\Events\WorkflowAction;

use App\Models\WorkflowAction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkflowTriggerActionTriggered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public WorkflowAction $action) {}
}
