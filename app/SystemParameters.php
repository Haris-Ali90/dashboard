<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class SystemParameters extends Model
{

    use SoftDeletes;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'system_parameters';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'key',
        'value',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes setters.
     *
     * @var array
     */

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = trim(strtolower($value));
    }


    // get key value
    public static function  getKeyValue($keys)
    {
        if( gettype($keys) == 'string')
        {
            return SystemParameters::where('key',$keys)->first();
        }
        elseif( gettype($keys) == 'array' )
        {
            return SystemParameters::whereIn('key',$keys)->get()->pluck([],'key'); //->pluck('value','key')->toAraay();
        }

    }
}
