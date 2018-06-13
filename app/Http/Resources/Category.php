<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
          'creationDate' => (string) $this->created_at->toDayDateTimeString(),
          'lastChange' => (string) $this->updated_at->toDayDateTimeString(),
          'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at->toDayDateTimeString() : null,
          'links' => [
            [
              'relation' => 'self',
              'href' => route('categories.show', $this->id),
            ],
            [
              'relation' => 'category.buyers',
              'href' => route('categories.buyers.index', $this->id),
            ],
            [
              'relation' => 'category.products',
              'href' => route('categories.products.index', $this->id),
            ],
            [
              'relation' => 'category.sellers',
              'href' => route('categories.sellers.index', $this->id),
            ],
            [
              'relation' => 'category.transactions',
              'href' => route('categories.transactions.index', $this->id),
            ],
          ],
        ];
    }

    public static function originalAttribute($index) {
      $attributes = [
        'identifier' => 'id',
        'title' => 'name',
        'details' => 'description',
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
        'created_at' => 'creationDate',
        'updated_at' => 'lastChange',
        'deleted_at' => 'deletedDate',
      ];

      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
