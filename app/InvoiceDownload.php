<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDownload extends Model
{
    public function order()
    {
    	return $this->belongsTo('App\Order','order_id','id');
    }

    public function variant()
    {
    	return $this->belongsTo('App\AddSubVariant','variant_id','id')->withTrashed();
    }

    public function seller(){
    	return $this->belongsTo('App\User','vender_id','id');
    }

    public function payouts(){
        return $this->hasMany('App\SellerPayout','orderid');
    }
}
