<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
          'title' => (string) $this->name,
          'details' => (string) $this->description,
          'stock' => (int) $this->quantity,
          'situation' => (string) $this->status,
          'picture' => url("img/{$this->image}"),
          'seller' => (int) $this->seller_id,
          'creationDate' => (string) $this->created_at->toDayDateTimeString(),
          'lastChange' => (string) $this->updated_at->toDayDateTimeString(),
          'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at->toDayDateTimeString() : null,
          'links' => [
            [
              'relation' => 'self',
              'href' => route('products.show', $this->id),
            ],
            [
              'relation' => 'product.buyers',
              'href' => route('products.buyers.index', $this->id),
            ],
            [
              'relation' => 'product.categories',
              'href' => route('products.categories.index', $this->id),
            ],
            [
              'relation' => 'product.transactions',
              'href' => route('products.transactions.index', $this->id),
            ],
            [
              'relation' => 'seller',
              'href' => route('sellers.show', $this->seller_id),
            ],
          ],
        ];
    }

    public static function originalAttribute($index) {
      $attributes = [
        'identifier' => 'id',
        'title' => 'name',
        'details' => 'description',
        'stock' => 'quantity',
        'situation' => 'status',
        'picture' => 'image',
        'seller' => 'seller_id',
        'creationDate' => 'created_at',
        'lastChange' => 'updated_at',
        'deletedDate' => 'deleted_at',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index) {
      $attributes = [
        'id' => 'identifier',
        'name' => 'title',
        'description' => 'details',
        'quantity' => 'stock',
        'status' => 'situation',
        'image' => 'picture',
        'seller_id' => 'seller',
        'created_at' => 'creationDate',
        'updated_at' => 'lastChange',
        'deleted_at' => 'deletedDate',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
