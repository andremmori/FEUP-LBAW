<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotion';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The user that promotes/demotes.
     */
    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The user that receives promotion/demotion.
     */
    public function receiver()
    {
        return $this->belongsTo('App\User');
    }

}