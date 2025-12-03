<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

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


        Gate::define('isAdmin', function (User $user) {
            return $user->hasRole('admin'); 
        });

        Gate::define('isStaff', function (User $user) {
            return $user->hasRole('staff'); 
        });

        Gate::define('isUtente', function (User $user) {
            return $user->hasRole('utente'); 
        });
    }
}
