<?php

namespace App;

use App\Http\Traits\FlagMetaDataFunctions;
use Illuminate\Database\Eloquent\Model;

class FlagCategoryMetaData extends Model
{
    use FlagMetaDataFunctions;

    protected $table = 'flag_category_meta_data';

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

