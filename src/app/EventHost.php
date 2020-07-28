<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventHost extends Model
{
    protected $table = 'eventhost';
    protected $primaryKey = ['iduser', 'idevent'];
    public $incrementing = false;
    
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The event.
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    /**
     * The host of the event.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
