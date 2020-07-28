<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';

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
