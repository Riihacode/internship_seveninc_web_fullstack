<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
// use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\Supplier;
use App\Policies\UserPolicy;
use App\Policies\ProductPolicy;
use App\Models\StockTransaction;
use App\Policies\CategoryPolicy;
use App\Policies\ProductAttributePolicy;
use App\Policies\SupplierPolicy;
use App\Policies\StockTransactionPolicy;
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
    // protected $policies = [
    //     Product::class => ProductPolicy::class,
    // ];
    protected $policies = [
            User::class             => UserPolicy::class,
            Product::class          => ProductPolicy::class,
            Category::class         => CategoryPolicy::class,
            Supplier::class         => SupplierPolicy::class,
            StockTransaction::class => StockTransactionPolicy::class,
            ProductAttribute::class => ProductAttributePolicy::class,
        ];


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Laravel 12 otomatis discover policy, 
        // jadi bisa kosong atau tetap didefinisikan manual seperti di atas.
        // $this->registerPolicies();
        $this->registerPolicies(); // tetap panggil manual
    }
}
