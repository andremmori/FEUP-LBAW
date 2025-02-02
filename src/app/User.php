<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'user';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'birthdate', 'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The cards this user owns.
     */
     public function cards() {
      return $this->hasMany('App\Card');
    }

    /**
     * The events this user hosts.
     */
    public function hostedEvents()
    {
        return $this->belongsToMany('App\Event', 'eventhost', 'iduser' , 'idevent');
    }

    /**
     * The events this user participates.
     */
    public function participatedEvents()
    {
        return $this->belongsToMany('App\Event', 'ticket', 'iduser', 'idevent');
    }

    public function logout()
    {
        return redirect()->route('logout');
    }
}