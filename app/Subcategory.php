<?php

namespace App;
use App\Category;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Translatable\HasTranslations;

class Subcategory extends Model
{

    use Sortable;
    use HasTranslations;

    public $translatable = ['title','description'];

	protected $fillable = [
		'title','image','description','parent_cat','status','featured','icon','position'
	];
    
    public function category(){
    	return $this->belongsTo(Category::class,'parent_cat');
    }

    public function childcategory(){
    	return $this->hasMany('App\Grandcategory','subcat_id');
    }

    public function products()
    {
    	return $this->hasMany('App\Product','child');
    }

}
