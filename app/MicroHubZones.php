<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;
use App\Review;
use Illuminate\Support\Facades\Mail;

class MicroHubZones extends Authenticatable
{
    protected $table = 'microhub_zones_external';
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'id','zone_id','hub_id',
    ];


}
