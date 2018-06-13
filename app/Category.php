<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\Category as CategoryResource;

class Category extends Model
{
  use SoftDeletes;

  public $transformer = CategoryResource::class;
  
  protected $dates = ['deleted_at'];

  protected $fillable = [
    'name',
    'description',
  ];

  protected $hidden = [
    'pivot'
  ];

  public function products() {
    return $this->belongsToMany(Product::class);
  }
}
