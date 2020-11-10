<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class multiCurrency extends Model
{
     public static function getCurrencyRate($price){

        $getCurrency = multiCurrency::first();

        return $USD = round($price/$getCurrency->rate+$getCurrency->add_amount,2);

    }

     public function currency(){
    	return $this->belongsTo('App\CurrencyList','currency_id','id');  
     }
}
