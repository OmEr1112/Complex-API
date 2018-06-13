<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;

class ProductCategoryController extends ApiController
{
    public function __construct() {
      $this->middleware('client.credentials')->only(['index']);
      $this->middleware('auth:api')->except(['index']);
      $this->middleware('scope:manage-products')->except(['index']);
      $this->middleware('can:add-category,product')->only(['update']);
      $this->middleware('can:delete-category,product')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {

      $categories = $product->categories;

      return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
      // attach, it doesn't take into account if the attachemnt already exist
      // sync, it detach all the previous attachments
      // syncWithoutDetaching, it doesn't delete the previous attachments and doesn't attach again

      $product->categories()->syncWithoutDetaching([$category->id]);

      $categories = $product->categories;

      return $this->showAll($categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
      if(!$product->categories()->find($category->id)) {
        return $this->errorResponse('The specified category is not a category of this product.', 404);
      }

      $product->categories()->detach($category->id);

      $categories = $product->categories;

      return $this->showAll($categories);
    }
}
