<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Amazon_count extends Authenticatable
{
    protected $table = 'amazon_dashboard_count';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'total','otd','sorter_order_count','hub_deliver_order_count','deliver_order_count','mainfestOrders','failedOrders','returnOrder','updated_at','created_at','deleted_at','vendor_id'
    ];


}
