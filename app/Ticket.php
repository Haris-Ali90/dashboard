<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Authenticatable
{
    protected $table = 'tickets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zone_id','area_id','category_id','NIC_number','description','address'
    ];

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function area()
    {
        return $this->belongsTo('App\Area');
    }
}
