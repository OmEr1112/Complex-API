<?php

namespace App\Policies;

use App\User;
use App\Seller;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class SellerPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the seller.
     *
     * @param  \App\User  $user
     * @param  \App\Seller  $seller
     * @return mixed
     */
    public function view(User $user, Seller $seller)
    {
      return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can sell a product.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function sale(User $user, User $seller)
    {
      return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\User  $user
     * @param  \App\Seller  $seller
     * @return mixed
     */
    public function editProduct(User $user, Seller $seller)
    {
      return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\User  $user
     * @param  \App\Seller  $seller
     * @return mixed
     */
    public function deleteProduct(User $user, Seller $seller)
    {
      return $user->id === $seller->id;
    }
}
