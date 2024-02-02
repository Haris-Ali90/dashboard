<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {

    protected $table = 'services';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','category_id','service_name','service_image','header_image','description','price'
    ];

    public function subcategory()
    {
        return $this->hasMany('App\Subcategory','parent_id');
    }
    


}
