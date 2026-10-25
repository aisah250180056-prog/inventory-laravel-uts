<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('staff', function (User $user) {
            return $user->isStaff();
        });

        Gate::define('customer', function (User $user) {
            return $user->isCustomer();
        });

        // Akses fitur operasional internal (Admin & Staff)
        Gate::define('backoffice', function (User $user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('manage-sales', function (User $user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('manage-purchases', function (User $user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('cancel-transaction', function (User $user) {
            return $user->isAdmin();
        });
    }
}
