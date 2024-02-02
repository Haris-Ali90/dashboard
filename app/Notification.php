<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Authenticatable
{
    //use SoftDeletes;

    //const ROLE_ADMIN = 1;
    //const ROLE_MEMBER = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'sender_id','reciever_id', 'message', 'action_type','action_id','request_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function ReceiverDetail()
    {
        return $this->belongsTo('App\User', 'reciever_id' );
    }

    /*public function vehicleImages()
    {
        return $this->hasMany('App\VehicleImage', 'vehicle_id' );
    }*/

    


}
