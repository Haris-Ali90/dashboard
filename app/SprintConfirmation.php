<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SprintConfirmation extends Model
{

    protected $table = 'sprint__confirmations';
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



}
