<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Providers;

use App\Actions\RefreshDeploymentId;
use App\Actions\Workflow\SetWorkflowAsTestedIfPossible;
use App\Actions\WorkflowAction\CreateWorkflowActionHistory;
use App\Actions\WorkflowAction\ExecuteWorkflowAction;
use App\Actions\WorkflowAction\RefreshOAuthToken;
use App\Events\Workflow\WorkflowDeploymentTriggered;
use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Events\WorkflowAction\WorkflowTriggerActionTriggered;
use App\Listeners\Workflow\DeployWorkflow;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkflowActionTriggered::class => [
            RefreshOAuthToken::class,
            RefreshDeploymentId::class,
            ExecuteWorkflowAction::class,
        ],
        WorkflowTriggerActionTriggered::class => [
            RefreshOAuthToken::class,
            ExecuteWorkflowAction::class,
        ],
        WorkflowActionExecuted::class => [
            CreateWorkflowActionHistory::class,
        ],
        WorkflowDeploymentTriggered::class => [
            // flip these so that if deployed, refreshdeploymentId undeploys watcher before setAsTested runs
            RefreshDeploymentId::class,
            SetWorkflowAsTestedIfPossible::class,
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
