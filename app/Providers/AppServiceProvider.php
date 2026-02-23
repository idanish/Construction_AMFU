<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Department;
use App\Models\Budget;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Observers\BaseObserver;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator; 

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
        Paginator::useBootstrapFive();

        if (Schema::hasTable('settings')) {
            $settings = Setting::pluck('value', 'type')->all();
            view()->share('appSettings', $settings);
        }
    }
}
