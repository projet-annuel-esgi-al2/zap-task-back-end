<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Providers;

use App\Actions\WorkflowAction\CreateWorkflowActionHistory;
use App\Events\WorkflowAction\WorkflowActionExecuted;
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
