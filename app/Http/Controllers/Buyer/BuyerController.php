<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Buyer;
use App\Http\Resources\CustomUser as UserResource;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    public function __construct() {
      parent::__construct();
      $this->middleware('scope:read-general')->only(['show']);
      $this->middleware('can:view,buyer')->only(['show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->allowedAdminAction();
      
      $buyers = Buyer::all();

      return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
      //$buyer = Buyer::findOrFail($id);

      return $this->showOne($buyer);
    }

}