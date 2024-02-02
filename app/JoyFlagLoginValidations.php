<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoyFlagLoginValidations extends Model
{

    use SoftDeletes;

    protected $table = 'joey_flag_login_validations';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function joeyName()
    {
        return $this->belongsTo(Joey::class,'joey_id','id');
    }


}

