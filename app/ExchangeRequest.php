<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeRequest extends Model
{

    protected $table = 'exchange_request';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function OldOrderTracking()
    {
        return $this->belongsTo(MerchantIds::class,'tracking_id_exchange','tracking_id');
    }

}
