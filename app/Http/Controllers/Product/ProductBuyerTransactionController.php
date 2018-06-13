<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Transaction;
use App\Http\Resources\Transaction as TransactionResource;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct() {
      parent::__construct();
      $this->middleware('transform.input:' . TransactionResource::class)->only(['store']);
      $this->middleware('scope:purchase-product')->only(['store']);
      $this->middleware('can:purchase,buyer')->only(['store']);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
      $rules = [
        'quantity' => 'required|integer|min:1'
      ];
      $quantity = $request->validate($rules);


      if ($buyer->id === $product->seller_id) {
        return $this->errorResponse('The buyer must be different from the seller.', 409);
      }

      if (!$buyer->isVerified()) {
        return $this->errorResponse('The buyer must be a verified user.', 409);
      }

      if (!$product->seller->isVerified()) {
        return $this->errorResponse('The seller must be a verified user.', 409);
      }

      if (!$product->isAvailable()) {
        return $this->errorResponse('The product is not available', 409);
      }

      if ($product->quantity < $request->quantity) {
        return $this->errorResponse('The product does not have enough units for this transaction.', 409);
      }

      //return $quantity['quantity'];

      return DB::transaction(function() use ($quantity, $product, $buyer) {
        $product->quantity -= $quantity['quantity'];
        $product->save();

        $transaction = Transaction::create([
          'quantity' => $quantity['quantity'],
          'buyer_id' => $buyer->id,
          'product_id' => $product->id,
        ]);

        return $this->showOne($transaction, 201);
      });
    }
}