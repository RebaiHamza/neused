<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usedProductImage extends Model
{
    protected $fillable = [
        'image1',
        'image2',
        'image3',
        'image4',
        'image5',
        'image6',
        'main_image',
        'pro_id'
    ];
}
