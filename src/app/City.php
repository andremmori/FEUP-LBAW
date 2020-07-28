<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $table = 'city';

    /**
     * The country this city belongs to
     */
    public function country()
    {
        return $this->belongsTo('App\Country', 'idcountry');
    }

    /**
     * The events in this city.
     */
    public function cities()
    {
        return $this->hasMany('App\Events');
    }
}
