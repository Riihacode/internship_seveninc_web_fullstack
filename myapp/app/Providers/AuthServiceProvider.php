<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    // /**
    //  * Register services.
    //  */
    public function register(): void
    {
        //
    }

    // /**
    //  * Bootstrap services.
    //  */
    // public function boot(): void
    // {
    //     //
    // }

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Laravel 12 otomatis discover policy, 
        // jadi bisa kosong atau tetap didefinisikan manual seperti di atas.
        // $this->registerPolicies();
    }
}
