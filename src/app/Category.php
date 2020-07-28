<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    /**
     * The events of this category.
     */
    public function events(){
        return $this->hasMany('App\Event');
    }
}
