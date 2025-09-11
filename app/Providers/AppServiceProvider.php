<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Department;
use App\Models\Budget;
use App\Observers\BaseObserver;
use App\Observers\UserObserver;

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
        Department::observe(BaseObserver::class);
        Budget::observe(BaseObserver::class);
        User::observe(UserObserver::class);
    }
}
