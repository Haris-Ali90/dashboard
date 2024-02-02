<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class CustomerSupportReturnNotes extends Model
{
    protected $table = 'customer_support_return_notes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'note_body', 'rarph_ref_id', 'creator_id'
    ];


}
