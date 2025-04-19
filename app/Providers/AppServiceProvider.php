<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FormBuilder;
use App\Observers\FormBuilderObserver;

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
        FormBuilder::observe(FormBuilderObserver::class);
    }
}
