<?php

namespace App;
use App\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    public $translatable = ['title','description'];

	protected $fillable = [
		'title','description','status','image','featured','icon','position'
	];
	
    public function subcategory(){
    	return $this->hasMany('App\Subcategory','parent_cat');
    }

    public function products()
    {
    	return $this->hasMany('App\Product','category_id');
    }


}
