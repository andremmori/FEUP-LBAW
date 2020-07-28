<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The location of the event.
     */
    public function location()
    {
        return $this->belongsTo('App\City', 'idlocation');
    }

    /**
     * The category of the event.
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'idcategory');
    }

    /**
     * The category of the event.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'idevent');
    }

    /**
     * Hosts of the event
     */
    public function hosts(){
        return $this->belongsToMany('App\User', 'eventhost', 'idevent', 'iduser');
    }

    /**
     * Hosts of the event
     */
    public function participants()
    {
        return $this->belongsToMany('App\User', 'ticket', 'idevent', 'iduser');
    }

    /**
     * Photos of the event
     */
    public function photos()
    {
        return $this->hasMany('App\Photo', 'idevent');
    }

    /**
     * Photos of the event
     */
    public function cover()
    {
        return $this->hasOne('App\Photo', 'idevent');
    }

    /**
     * Comments of the event
     */
    public function comment()
    {
        return $this->hasMany('App\Comment', 'idcomment');
    }
}
