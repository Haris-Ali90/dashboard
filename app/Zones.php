<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zones extends Model {

    protected $table = 'zones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zone_name','location_longitude','location_latitude','radius'
    ];

}
