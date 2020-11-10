<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Grandcategory extends Model
{
     use HasTranslations;

     public $translatable = ['title','description'];

     protected $fillable = [
  		  'title','image','description','subcat_id','parent_id','status','featured','position'
  	 ];

    public function subcategory(){
    	return $this->belongsTo('App\Subcategory','subcat_id','id');
    }

    public function category(){
    	return $this->belongsTo(Category::class,'parent_id');
    }

    public function products()
    {
    	return $this->hasMany(Product::class,'grand_id');
    }
}
