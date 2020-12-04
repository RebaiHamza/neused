<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission_used extends Model
{
    protected $fillable = [
		'used_id','rate','type','status',
	];

	public function product()
	{
    	return $this->belongsTo('App\Product','used_id');  
    }
}
