<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\CustomerFlagCategoryFunctions;

class CustomerFlagCategories extends Model
{
    use CustomerFlagCategoryFunctions;

    protected $table = 'customer_flag_categories';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];


    public function flagMetaData()
    {
        return $this->hasMany(FlagCategoryMetaData::class, 'category_ref_id', 'id')->whereNull('deleted_at');
    }

    public function flagIncident()
    {
        return $this->hasMany(CustomerFlagCategoryValues::class, 'category_ref_id', 'id');
    }

    public function getChilds()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}

