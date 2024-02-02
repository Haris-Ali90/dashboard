<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model {

    protected $table = 'user_services';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id','category_id','service_id'
    ];
    
    	protected $casts = ['user_id' => 'integer','category_id' => 'integer','service_id' => 'integer'];
    //  protected $visible = [ 'id', 'user_id','category_id','service_id'];

    public function Services()
    {
        
        return $this->belongsTo('App\Service','service_id');
    }
    


}
