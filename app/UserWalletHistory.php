<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWalletHistory extends Model
{
    public function wallet(){
    	return $this->belongsTo('App\UserWallet','id');
    }
}
