<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Providers;

use App\Actions\WorkflowAction\CreateWorkflowActionHistory;
use App\Actions\WorkflowAction\ExecuteWorkflowAction;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Listeners\Workflow\DeployWorkflow;
use App\Listeners\Workflow\RefreshDeploymentId;
use App\Listeners\WorkflowAction\RefreshOAuthToken;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkflowActionTriggered::class => [
            RefreshOAuthToken::class,
            ExecuteWorkflowAction::class,
        ],
        WorkflowActionExecuted::class => [
            CreateWorkflowActionHistory::class,
        ],
        WorkflowDeploymentTriggered::class => [
            RefreshDeploymentId::class,
            DeployWorkflow::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
