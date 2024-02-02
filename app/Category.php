<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'product_name','product_image','parent_id'
    ];

    public function subcategory()
    {
        return $this->hasMany('App\Subcategory','parent_id');
    }
    


}
