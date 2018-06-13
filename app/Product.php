<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\Product as ProductResource;

class Product extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  const AVAILABLE_PRODUCT = 'available';
  const UNAVAILABLE_PRODUCT = 'unavailable';

  protected $fillable = [
    'name',
    'description',
    'quantity',
    'status',
    'image',
    'seller_id',
  ];

  protected $hidden = [
    'pivot'
  ];

  public $transformer = ProductResource::class;

  public function isAvailable() {
    return $this->status === Product::AVAILABLE_PRODUCT;
  }

  public function categories() {
    return $this->belongsToMany(Category::class);
  }

  public function seller() {
    return $this->belongsTo(Seller::class);
  }

  public function transactions() {
    return $this->hasMany(Transaction::class);
  }
}
