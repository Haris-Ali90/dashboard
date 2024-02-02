<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ReturnReattemptProcess extends Model
{
    protected $table = 'return_and_reattempt_process_history';
    use SoftDeletes;
     /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }

    /*
    * Relation with User Model For Verified Order
    * */
    public function VerifiedByUser()
    {
        return $this->belongsTo( User::class,'verified_by', 'id');
    }

    //protected $appends = ['route_id'];

    /**
     * Scope a query to only include customer support.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomerSupport($query)
    {
        return $query->where('process_type', '=', 'customer_support')->where('deleted_at',null);
    }
	
	/**
     * Get Notes.
     */
    public function CustomerNotes()
    {
        return $this->hasMany( CustomerSupportReturnNotes::class,'rarph_ref_id', 'id');
    }


}
