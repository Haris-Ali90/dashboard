<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Walmart extends Authenticatable
{
    protected $table = 'walmart_dashboard';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'id','store_name','walmart_order_num','joey_name','sprint_id','status_id','schedule_pickup','compliant_pickup','arrival_time','departure_time','dropoff_eta','compliant_dropoff','pickup_eta','delivery_time','address','updated_at','created_at','deleted_at'
    ];


}
