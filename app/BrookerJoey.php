<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrookerJoey extends Model
{

    protected $table = 'brooker_joey';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'id', 'name', 'created_at','updated_at','deleted_at',
    // ];

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];


    public function brooker()
    {
        return $this->belongsTo(BrookerUser::class,'brooker_id','id');
    }

}
