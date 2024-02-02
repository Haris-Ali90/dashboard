<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{

    protected $table = 'payment_info';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'card_number', 'expiry_date','cvv','billiing_person_name','billiing_person_address','billing_person_city'
    ];




}
