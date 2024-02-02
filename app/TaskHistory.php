<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;


class TaskHistory extends Authenticatable
{
    //use SoftDeletes;
  
    protected $table = 'sprint__tasks_history';
    
    public $timestamps = false;
}
