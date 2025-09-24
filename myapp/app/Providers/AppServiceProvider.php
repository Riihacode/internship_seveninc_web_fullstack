<?php

namespace App\Providers;

use App\Models\StockTransaction;
use Illuminate\Support\ServiceProvider;
use App\Observers\StockTransactionObserver;

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
    // public function boot(): void
    // {
    //     //
    // }
    public function boot(): void
    {
        StockTransaction::observe(StockTransactionObserver::class);
    }
}
