<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model {

    protected $table = 'zone';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zone_name','location_longitude','location_latitude','radius'
    ];

    /*public function subcategory()
    {
        return $this->hasMany('App\Subcategory','parent_id');
    }*/
     /*public function area()
    {
        return $this->hasMany('App\Area','id');
    }  */
}
