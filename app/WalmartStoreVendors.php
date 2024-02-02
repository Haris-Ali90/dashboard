<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;



class WalmartStoreVendors extends Model
{

    /**
     * Table name.
     *
     * @var array
     */
    protected $table = 'walmart_store_vendors';



    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];


}