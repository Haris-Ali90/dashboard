<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Postfile;
use Config;
class Post extends Model
{
	public static $likeUserId = 0;
    protected $table = 'post';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'text','latitude','longitude','profile_video','thumbnail_image','file_type','created_at','updated_at'
    ];

    protected $appends = [ 'comments_count','likes_count' ,'is_like','image_count','file_count','profile_video_url','thumb_image'];

    public function getThumbImageAttribute() {

        $thumbImage = asset(Config::get('constants.front.dir.getthumbnailVideoPath') . ($this->thumbnail_image ?: Config::get('constants.front.default.profilePic')));
        return $thumbImage;
    }
    public function getIsLikeAttribute()
    {
        if(self::$likeUserId) {
            $instance = $this->LikeDetail()->getQuery()->where('user_id',self::$likeUserId	)->first();
            return ($instance) ? 1 : 0;
        }
        return 0;
    }
    public function getProfileVideoUrlAttribute()
    {

        $profileVideoUrl = asset(Config::get('constants.front.dir.getpromoVideoPath') . ($this->profile_video));


        return $profileVideoUrl;
    }
	
	public function scopeWithIsLike($query, $user_id=0){

        self::$likeUserId = $user_id;
		
        return $query;
    }
	
	
    public function UserDetail()
    {
        return $this->belongsTo('App\User', 'user_id' );
    }

    public function FileDetail()
    {
        return $this->hasMany('App\Postfile', 'post_id' );
    }
    public function LikeDetail()
    {
        return $this->hasMany('App\Like', 'post_id' );
    }
    public function CommentDetail()
    {
        return $this->hasMany('App\Comment', 'post_id' );
    }

    public function getCommentsCountAttribute()
    {
       // dd($this->CommentDetail);

        return $this->CommentDetail()->count();
    }

    public function getLikesCountAttribute()
    {
        // dd($this->CommentDetail);
        // $this->LikeDetail == $this->LikeDetail()->get()
        // $this->LikeDetail() == Postfile::query()->where('post_id', $this->id)
        return $this->LikeDetail()->count();
    }

    public function getImageCountAttribute()
    {
        $totalimagecount = $this->FileDetail()->where('file_type','image')->count();
        return $totalimagecount;
    }

    public function getFileCountAttribute()
    {
        $totalfilecount = Postfile::where('file_type','file')->where('post_id', $this->id)->count();
        return $totalfilecount;
    }

}
