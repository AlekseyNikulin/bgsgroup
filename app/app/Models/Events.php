<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'city', 'date_begin','is_active', 'created_at', 'updated_at'
    ];

    public function users_events(){
        return $this->hasMany('\App\Models\UsersEvents','event_id');
    }
}
