<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDetails extends Authenticatable
{
    protected $table = 'cart_details';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id','delivery_name','delivery_email','delivery_address','delivery_phone','sub_total','discount','delivery_charges','total_amount','requirements'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'remember_token',
    ];

    public function UserDetail()
    {
        return $this->belongsTo('App\User', 'user_id' );
    }

}
