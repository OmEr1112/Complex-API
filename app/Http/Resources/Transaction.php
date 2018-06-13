<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'identifier' => (int) $this->id,
          'quantity' => (int) $this->quantity,
          'buyer' => (int) $this->buyer_id,
          'product' => (int) $this->product_id,
          'creationDate' => (string) $this->created_at->toDayDateTimeString(),
          'lastChange' => (string) $this->updated_at->toDayDateTimeString(),
          'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at->toDayDateTimeString() : null,
          'links' => [
            [
              'relation' => 'self',
              'href' => route('transactions.show', $this->id),
            ],
            [
              'relation' => 'product.categories',
              'href' => route('transactions.categories.index', $this->id),
            ],
            [
              'relation' => 'product.seller',
              'href' => route('transactions.sellers.index', $this->id),
            ],
            [
              'relation' => 'buyer',
              'href' => route('buyers.show', $this->buyer_id),
            ],
            [
              'relation' => 'product',
              'href' => route('products.show', $this->product_id),
            ],
          ],
        ];
    }

    public static function originalAttribute($index) {
      $attributes = [
        'identifier' => 'id',
        'quantity' => 'quantity',
        'buyer' => 'buyer_id',
        'product' => 'product_id',
        'creationDate' => 'created_at',
        'lastChange' => 'updated_at',
        'deletedDate' => 'deleted_at',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index) {
      $attributes = [
        'id' => 'identifier',
        'quantity' => 'quantity',
        'buyer_id' => 'buyer',
        'product_id' => 'product',
        'created_at' => 'creationDate',
        'updated_at' => 'lastChange',
        'deleted_at' => 'deletedDate',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
