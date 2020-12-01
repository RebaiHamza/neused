<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use Spatie\Translatable\HasTranslations;

class Help extends Model
{
    use InteractsWithViews;

	use HasTranslations;

	public $translatable = ['help_name', 'help_file'];

    protected $fillable = [
        'help_name', 'help_file'
    ];
}
