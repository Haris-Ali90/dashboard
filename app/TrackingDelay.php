<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackingDelay extends Model {

    protected $table = 'tracking_delay';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'date','tracking_id'
    ];




}
