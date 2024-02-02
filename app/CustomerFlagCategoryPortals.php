<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class CustomerFlagCategoryPortals extends Model
{
    protected $table = 'customer_flag_category_portals';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function FlagCategory()
    {
        return $this->hasMany(CustomerFlagCategories::class, 'category_ref_id', 'id');
    }
	
}

