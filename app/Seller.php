<?php

namespace App;

use App\Scopes\SellerScope;
use App\Http\Resources\Seller as SellerResource;


class Seller extends User
{
  public $transformer = SellerResource::class;

  protected static function boot() {
    parent::boot();

    static::addGlobalScope(new SellerScope);
  }

  public function products() {
    return $this->hasMany(Product::class);
  }

  // thats how you define scopes and called withProducts()
  // this is not being called anywhere, here for demostration
  public function scopeWithProducts($query) {
    return $query->has('products');
  }
}
