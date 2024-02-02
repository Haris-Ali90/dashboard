<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddCart extends Authenticatable
{
    protected $table = 'add_to_cart';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id','service_id','quantity','sub_total'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'remember_token',
    ];

    public function Service()
    {
        return $this->belongsTo('App\Service', 'service_id' );
    }
	 //'test',

}
