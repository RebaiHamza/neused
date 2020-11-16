<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddProductVariant extends Model
{

    protected $fillable = [
    	'attr_name','attr_value','pro_id','created_at','updated_at'];

    public function productvar()
    {
    	return $this->belongsTo('App\Product','pro_id','id')->withTrashed();
    }

    public function getattrname()
    {
    	return $this->belongsTo('App\ProductAttributes','attr_name','id');
    }

    public function getattrValue()
    {
    	return $this->belongsTo('App\ProductValues','attr_value','id');
    }

     protected $casts = [
    'attr_value' => 'array', // Will convarted to (Array)
    ];

}
