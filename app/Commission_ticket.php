<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission_ticket extends Model
{
    protected $fillable = [
		'ticket_id','rate','type','status',
	];

	public function product()
	{
    	return $this->belongsTo('App\Product','ticket_id');  
    }
}
