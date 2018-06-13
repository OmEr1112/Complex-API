<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\Resource;
use App\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\User;
use App\Mail\UserMailChanged;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Schema::defaultStringLength(191);

      // sending mail when user created
      User::created(function($user) {

        retry(5, function() use ($user) {
          Mail::to($user)->send(new UserCreated($user));
        }, 200);
      });

      // sending mail when user created
      User::updated(function($user) {

        if($user->isDirty('email')) {
          
          retry(5, function() use ($user) {
            Mail::to($user)->send(new UserMailChanged($user));
          }, 200);
        }
      });

      // changing product availability
      Product::updated(function($product) {
        if ($product->quantity === 0 && $product->isAvailable()) {
          $product->status = Product::UNAVAILABLE_PRODUCT;
          $product->save();
        }
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
