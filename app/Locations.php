<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{

    protected $table = 'locations';

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }


}
