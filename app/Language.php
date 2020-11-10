<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'locales';

    protected $fillable = ['lang_code','name','def'];

    public $timestamps = false;
}
