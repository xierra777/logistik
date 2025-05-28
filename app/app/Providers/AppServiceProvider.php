<?php

namespace App\Providers;

use App\Models\Transaction;

use App\Observers\TransactionObserver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

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
    public function boot()
    {
        // Model::preventLazyLoading(true);
    }
}
