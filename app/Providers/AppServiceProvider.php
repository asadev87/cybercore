<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\Module;
use App\Policies\ModulePolicy;
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
        Gate::policy(Module::class, ModulePolicy::class);
    }
}
