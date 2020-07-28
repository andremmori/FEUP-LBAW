<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    /**
     * The cities of this country.
     */
    public function cities()
    {
        return $this->hasMany('App\City');
    }
}
