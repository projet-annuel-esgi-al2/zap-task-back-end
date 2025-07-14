<?php

namespace App\Events\Workflow;

use App\Models\Workflow;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkflowDeploymentTriggered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Workflow $workflow) {}
}
