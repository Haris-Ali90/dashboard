<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderImage extends Model
{

    protected $table = 'order_images';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'tracking_id','task_id','image'
    ];




}
