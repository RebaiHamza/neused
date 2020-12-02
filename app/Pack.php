<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use InteractsWithViews;
use HasTranslations;
    
class Pack extends Model
{

	public $translatable = ['title', 'description', 'price', 'status'];

    protected $fillable = [
        'title', 'description', 'price', 'status'
    ];
}
