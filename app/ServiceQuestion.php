<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Config;
class ServiceQuestion extends Model {

    protected $table = 'service_questions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'service_id','question','icon'
    ];
    protected $appends = ['icon_image'];
    
    public function getIconImageAttribute() {

        $iconImage = asset(Config::get('constants.front.dir.getQuestionPath') . ($this->icon ?: Config::get('constants.front.default.profilePic')));
        return $iconImage;
    }

    public function subcategory()
    {
        return $this->hasMany('App\Subcategory','parent_id');
    }
    


}
