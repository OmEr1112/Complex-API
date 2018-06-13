<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;


trait ApiResponser {

  private function successResponse($data, $code) {
    return response()->json($data, $code);
  }

  protected function errorResponse($message, $code) {
    return response()->json(['error' => $message, 'code' => $code], $code);
  }

  protected function showAll(Collection $collection, $code = 200) {
    if ($collection->isEmpty()) {
      return $this->successResponse(['data' => $collection], $code);
    }

    $transformer = $collection->first()->transformer;

    $collection = $this->filterData($collection, $transformer);
    $collection = $this->sortData($collection, $transformer);

    $rules = [
      'per_page' => 'integer|min:2|max:50',
    ];
    Validator::validate(request()->all(), $rules);

    $perPage = request()->has('per_page') ? (int) request()->per_page : 15;
    if ($collection->count() > $perPage) {
      $collection = $this->paginate($collection);
    }
    $transformedData = $transformer::collection($collection);

    $transformedData = $this->cacheResponse($transformedData);

    return $transformedData;
    //$this->successResponse([$transformedData], $code);
  }

  protected function showOne(Model $instance, $code = 200) {

    $transformer = $instance->transformer;
    $transformedData = new $transformer($instance);

    return $this->successResponse(['data' => $transformedData], $code);
  }

  public function showMessage($message, $code = 200) {
    return $this->successResponse($message, $code);
  }

  protected function filterData(Collection $collection, $transformer) {
    foreach (request()->query() as $query => $value) {
      $attribute = $transformer::originalAttribute($query);

      if (isset($attribute, $value)) {
        $collection = $collection->where($attribute, $value);
      }
    }

    return $collection;
  }

  protected function sortData(Collection $collection, $transformer) {
    if(request()->has('sort_by')) {
      $attribute = $transformer::originalAttribute(request()->query('sort_by'));

      $collection = $collection->sortBy($attribute); //sortBy->{$attribute}
    }

    return $collection;
  }

  // pagination function
  protected function paginate(Collection $collection) {
    $rules = [
      'per_page' => 'integer|min:2|max:50',
    ];

    Validator::validate(request()->all(), $rules);

    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 15;
    if (request()->has('per_page')) {
      $perPage = (int) request()->per_page;
    }

    $results = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $currentPage, [
      'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    $paginated->appends(request()->all());

    return $paginated;
  }

  protected function cacheResponse($data) {
    $url = request()->fullUrl();

    return Cache::remember($url, 30/60, function() use($data) {
      return $data;
    });
  }

}