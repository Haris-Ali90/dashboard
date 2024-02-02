<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorite';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = ['id', 'user_id', 'doctor_id', 'state', 'created_at', 'updated_at', 'UserDetail', 'UserDetails', 'qualifications', 'categories','user'];
    protected $fillable = [
        'id', 'user_id', 'state', 'doctor_id', 'UserDetail', 'UserDetaila', 'qualifications', 'categories',
    ];

    protected $appends= ['user'];

    public function UserDetail()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function UserDetails()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function qualifications()
    {
        return $this->hasMany('App\Qualification', 'user_id', 'doctor_id');
    }

    public function categories()
    {
        return $this->hasMany('App\UserCategory', 'user_id', 'doctor_id');
        //return $this->belongsToMany('App\Category', 'user_categories','doctor_id', 'category_id');
    }

    public function getUserAttribute(){
        return $this->UserDetails()->getQuery()->WithIsLike($this->doctor_id)->toSql();
    }
}