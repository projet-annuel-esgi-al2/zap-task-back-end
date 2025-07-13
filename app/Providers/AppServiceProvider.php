<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as AppEventServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceHttps();
        }

        AppEventServiceProvider::disableEventDiscovery();

        Model::shouldBeStrict($this->app->isLocal());
        // Set the default morph key to uuid to automatically generate the correct foreign id type in migrations.
        Builder::defaultMorphKeyType('uuid');

        // Turn morph map enforcement
        Relation::requireMorphMap();

        // Use sting mapping instead of model name in polymorphic relationship.
        Relation::morphMap([
            'user' => \App\Models\User::class,
        ]);
    }
}
