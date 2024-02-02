<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model {

    protected $table = 'area';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'area_name','zone_id'
    ];

    /*public function subcategory()
    {
        return $this->hasMany('App\Subcategory','parent_id');
    }*/

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
    


}
