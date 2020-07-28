<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photo';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The event.
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }

}
