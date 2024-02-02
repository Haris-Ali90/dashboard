<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model {

    protected $table = 'product_price';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'product_id','mandi_price','buying_price','selling_price','is_display','created_at','updated_at','deleted_at'
    ];

    public function subcategory()
    {
        return $this->hasMany('App\Subcategory','parent_id');
    }
    


}
