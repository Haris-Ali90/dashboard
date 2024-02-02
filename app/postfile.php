<?php

namespace App;
use Config;
use Illuminate\Database\Eloquent\Model;

class Postfile extends Model
{

    protected $table = 'postfiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id', 'post_id','file_name','file_type',
    ];

    protected $appends = ['file_link'];

    public function getFileLinkAttribute() {

        $fileLink =  asset(Config::get('constants.front.dir.GetprofilePicPath') . ($this->file_name ?: Config::get('constants.front.default.profilePic')));
        
        return $fileLink;
    }


}
