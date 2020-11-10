<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
    	'qty','pro_id','user_id','semi_total','variant_id','price_total','vender_id','ori_price','ori_offer_price','shipping'
    ];


     public function product()
     {
     	return $this->belongsTo('App\Product','pro_id')->withTrashed();  
     }

     public function variant()
     {
     	return $this->belongsTo('App\AddSubVariant','variant_id','id')->withTrashed();
     }
}
