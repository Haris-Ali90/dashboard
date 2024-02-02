<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerIncidents extends Model
{



    protected $table = 'customer_flag_incidents';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];
	
}

