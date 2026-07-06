<?php

namespace App\Providers;

use App\Models\Registration;
use App\Observers\RegistrationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Registration::observe(RegistrationObserver::class);
    }
}
