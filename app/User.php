<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'mobile', 'city_id', 'country_id', 'state_id', 'phone', 'image', 'website', 'status', 'remember_token', 'apply_vender', 'gender', 'is_verified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function city()
    {
        return $this->belongsTo(Allcity::class, 'city_id');
    }

    public function country()
    {
        return $this->belongsTo(Allcountry::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo('App\Allstate', 'state_id', 'id');
    }

    public function ticket()
    {
        return $this->hasMany('App\HelpDesk', 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address', 'user_id');
    }

    public function store()
    {
        return $this->hasOne('App\Store', 'user_id', 'id');
    }

    public function wishlist()
    {
        return $this->hasMany('App\Wishlist', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\UserReview', 'user');
    }

    public function banks()
    {
        return $this->hasMany('App\Userbank', 'user_id');
    }

    public function cart()
    {
        return $this->hasMany('App\Cart', 'user_id');
    }

    public function failedtxn()
    {
        return $this->hasMany('App\FailedTranscations', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'vender_id');
    }

    public function purchaseorder()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne('App\UserWallet', 'user_id', 'id');
    }

}
