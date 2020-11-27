<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BidProduct extends Model
{



    protected $fillable = [
    	 'pro_id','user_id','price_total','vender_id'
    ];


     public function product()
     {
     	return $this->belongsTo('App\Product','pro_id')->withTrashed();  
     }

     public function vender()
     {
         return $this->belongsTo('App\User', 'user_id', 'id');
     }
   






}