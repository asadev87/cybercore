<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\User;
use App\Policies\ModulePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Module::class => ModulePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a gate for viewing the reports page.
        // Only admins should have access to this.
        Gate::define('view-reports', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}