<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\Carbon;
use App\Buyer;
use App\Policies\BuyerPolicy;
use App\Seller;
use App\Policies\SellerPolicy;
use App\User;
use App\Policies\UserPolicy;
use App\Transaction;
use App\Policies\TransactionPolicy;
use App\Product;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action', function ($user) {
          return $user->isAdmin();
        });

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();

        Passport::tokensCan([
          'purchase-product' => 'Create a new transaction for a specific product',
          'manage-products' => 'Create, read, update, and delete products (CURD)',
          'manage-account' => 'Read an account data, id, name, email, if verified, and if admin (cannot read password). Modify your account data (email, and password). Cannot delete an account',
          'read-general' => 'Read general information like purchasing categories, purchased products, selling products, selling categories, transactions (puchases and sales)',
        ]);
    }
}
