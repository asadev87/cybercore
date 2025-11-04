<?php

namespace App\Providers;

use App\Listeners\CreditSignupBonus;
use App\Listeners\SyncUserRoleId;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Permission\Events\RoleAssigned;
use Spatie\Permission\Events\RoleRemoved;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            CreditSignupBonus::class,
        ],
        RoleAssigned::class => [
            SyncUserRoleId::class,
        ],
        RoleRemoved::class => [
            SyncUserRoleId::class,
        ],
    ];
}
