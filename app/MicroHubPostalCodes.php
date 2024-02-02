<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MicroHubPostalCodes extends Model
{

    protected $table = 'micro_hub_postal_codes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','hub_id','postal_code'
    ];




}
