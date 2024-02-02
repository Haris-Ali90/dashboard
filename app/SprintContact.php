<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SprintContact extends Model
{

    protected $table = 'sprint__contacts';


	protected $fillable = ['id', 'name', 'phone', 'email'];

}
