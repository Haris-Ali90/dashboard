<?php

namespace App;

use App\Http\Traits\BasicModelFunctions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class CtcEntries extends Authenticatable
{

    use BasicModelFunctions;

    protected $table = 'ctc_entries';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id', 'sprint_id','task_id','creator_id','route_id','ordinal','tracking_id','joey_id','joey_name','eta_time','store_name','customer_name','customer_address','status_id','weight','order_image'
    ];


}
