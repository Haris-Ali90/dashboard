<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ctc_count extends Authenticatable
{
    protected $table = 'ctc_dashboard_count';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'athub','atstore','outfordelivery','deliveredorder','total','otd','created_at','updated_at','deleted_at'
    ];


}
