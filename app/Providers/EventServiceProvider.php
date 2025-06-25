<?php

namespace App\Providers;

use App\Events\WorkflowAction\WorkflowActionExecuted;
use App\Listeners\WorkflowAction\CreateWorkflowActionHistory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WorkflowActionExecuted::class => [
            CreateWorkflowActionHistory::class,
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
