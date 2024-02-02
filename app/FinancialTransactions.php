<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\CustomerFlagCategoryFunctions;

class FinancialTransactions extends Model
{
    protected $table = 'financial_transactions';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];


}

