<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Seller extends JsonResource
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
          'name' => (string) $this->name,
          'email' => (string) $this->email,
          'isVerified' => (int) $this->verified,
          'creationDate' => (string) $this->created_at->toDayDateTimeString(),
          'lastChange' => (string) $this->updated_at->toDayDateTimeString(),
          'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at->toDayDateTimeString() : null,
          'links' => [
            [
              'relation' => 'self',
              'href' => route('sellers.show', $this->id),
            ],
            [
              'relation' => 'seller.categories',
              'href' => route('sellers.categories.index', $this->id),
            ],
            [
              'relation' => 'seller.products',
              'href' => route('sellers.products.index', $this->id),
            ],
            [
              'relation' => 'seller.buyers',
              'href' => route('sellers.buyers.index', $this->id),
            ],
            [
              'relation' => 'seller.transactions',
              'href' => route('sellers.transactions.index', $this->id),
            ],
            [
              'relation' => 'seller.profile',
              'href' => route('users.show', $this->id),
            ],
          ],
        ];
    }

    public static function originalAttribute($index) {
      $attributes = [
        'identifier' => 'id',
        'name' => 'name',
        'email' => 'email',
        'isVerified' => 'verified',
        'creationDate' => 'created_at',
        'lastChange' => 'updated_at',
        'deletedDate' => 'deleted_at',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index) {
      $attributes = [
        'id' => 'identifier',
        'name' => 'name',
        'email' => 'email',
        'verified' => 'isVerified',
        'created_at' => 'creationDate',
        'updated_at' => 'lastChange',
        'deleted_at' => 'deletedDate',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
