<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\Registered;
use App\Listeners\SendEmailVerificationNotification;
use App\Events\OrderCreated;
use App\Listeners\ProcessAutomaticVouchers;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Événement pour les commandes créées
        \App\Events\OrderCreated::class => [
            \App\Listeners\ProcessAutomaticVouchers::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
} 