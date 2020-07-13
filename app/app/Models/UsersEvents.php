<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersEvents extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'event_id', 'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function events(){
        return $this->hasOne('App\Models\Events', 'id', 'event_id');
    }
}
