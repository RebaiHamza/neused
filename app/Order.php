<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Order extends Model
{
     public $timestamps = false;

    
     protected $fillable = [
        'status', 'order_status','qty','user_id','pro_id','price','offer_price','pro_name','order_status','handlingcharge'
    ];

    protected $casts = [
        'pro_id' => 'array',
        'billing_address' => 'array',
        'vender_ids' => 'array',
        'main_pro_id' => 'array'
    ];

     public function product()
     {
     	return $this->belongsTo('App\Product','pro_id','id');  
     }

      public function billing()
     {
     	return $this->belongsTo('App\BillingAddress','billing_id');  
     }

     public function payAmount()
     {
        return $this->belongsTo('App\UserPayAmount','user_pay_id');  
     }

     public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function vender(){
        return $this->belongsTo(User::class,'vender_id');
    }

    public function invoices(){
        return $this->hasMany('App\InvoiceDownload','order_id');
    }

    public function orderlogs(){
        return $this->hasMany('App\OrderActivityLog','order_id');
    }

    public function cancellog(){
        return $this->hasMany('App\CanceledOrders','order_id');
    }

    public function refundlogs()
    {
        return $this->hasMany('App\Return_Product','main_order_id','id');
    }

    public function fullordercancellog(){
        return $this->hasMany('App\FullOrderCancelLog','order_id');
    }
}