<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The observer mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $observers = [
        \App\Models\Project::class => \App\Observers\ProjectObserver::class,
    ];

    protected $listen = [
            \App\Events\ProcurementApproved::class => [
            \App\Listeners\UpdateBudgetAndInvoice::class,
        ],
    ];


    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
