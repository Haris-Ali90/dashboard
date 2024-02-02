<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $table = 'vendors';


    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;

    }
    public function location()
    {
        return $this->belongsTo(Locations::class,'location_id','id');
    }


}
