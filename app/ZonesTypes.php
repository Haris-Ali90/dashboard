<?php

namespace App;

//use App\Models\Interfaces\TaxesInterface;
use Illuminate\Database\Eloquent\Model;

class ZonesTypes extends Model //implements TaxesInterface
{

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'zones_types';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [

    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * ORM Relation
     *
     * @var array
     */




}
