<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\CustomerFlagCategoryFunctions;

class JoeyTransactions extends Model
{

    protected $table = 'joey_transactions';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];
    public $timestamps= false;



}

