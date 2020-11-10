<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $fillable = [
		'name','email','phone', 'user_id','address','pin_code','defaddress','user_id','country_id','state_id','city_id',
	];
	
    public function user(){
    	return $this->belongsTo('App\User','user_id','id');
    }

    public function getstate(){
    	return $this->belongsTo('App\Allstate','state_id','id');
    }
}
