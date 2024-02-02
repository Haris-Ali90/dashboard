<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantIds extends Model
{
    protected $table = 'merchantids';

    public function Task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
     /**
     *get Joey route location table details
     */
    public function joeyRouteLocationDetail()
    {
        return $this->hasOne(JoeyRouteLocations::class,'task_id','task_id')->orderBy('id','desc');
    }
	
	public function OldTaskDetail()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
