<?php

namespace App;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SprintTasksHistory extends Model
{
    // use HasFactory;
    public $table = 'sprint__tasks_history';

    /**
     *get sprint confirmation table detail
     */
    public function getSprintConfirmationDetail()
    {
        return $this->hasOne(SprintConfirmation::class,'task_id','sprint__tasks_id')->orderBy('id',"DESC");
    }

}
