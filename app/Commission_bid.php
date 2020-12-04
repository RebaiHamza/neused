<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission_bid extends Model
{
    protected $fillable = [
		'bid_id','rate','type','status',
	];

	public function product()
	{
    	return $this->belongsTo('App\Product','bid_id');  
    }
}
