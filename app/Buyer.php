<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Buyer as BuyerResource;


class Buyer extends User
{
  public $transformer = BuyerResource::class;
  protected static function boot() {
    parent::boot();

    static::addGlobalScope('withTransactions', function (Builder $builder) {
        $builder->has('transactions');
    });
  }
  public function transactions() {
    return $this->hasMany(Transaction::class);
  }

  // public function scopeWithTransactions($query) {
  //   return $query->has('transactions');
  // }
}
