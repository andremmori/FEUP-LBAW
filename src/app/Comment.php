<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The location of the event.
     */
    public function event()
    {
        return $this->belongsTo('App\Event', 'idevent');
    }

    /**
     * The category of the event.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'iduser');
    }

}
