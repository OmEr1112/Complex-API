<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\User as UserResource;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable; use SoftDeletes; use HasApiTokens;

    protected $dates = ['deleted_at'];

    protected $table = 'users';

    public $transformer = UserResource::class;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    public function isVerified() {
      return $this->verified === User::VERIFIED_USER;
    }

    public function isAdmin() {
      return $this->admin === User::ADMIN_USER;
    }

    public static function generateVerificationCode() {
      return Str::random(40);
    }
    /**
     * Mutator for name
     *
     * @param [type] $name
     * @return void
     */
    public function setNameAttribute($name) {
      $this->attributes['name'] = strtolower($name);
    }

    /**
     * Accessor for name
     */

    public function getNameAttribute($name) {
      return ucwords($name);
    }

    /**
     * Mutator for email
     *
     * @param [type] $email
     * @return void
     */
    public function setEmailAttribute($email) {
      $this->attributes['email'] = strtolower($email);
    }
}
