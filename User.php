<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;
use App\Review;
class User extends Authenticatable
{
//    use SoftDeletes;
//
	 public static $likeUserId = 0;
     const ROLE_ADMIN                = 2;
     const Doctor_Role             = 1;
     const Patient_Role             = 0;
     const ROLE_USER                 = 0;
     const ROLE_DOCTOR               = 1;
//
//    const STATUS_INACTIVE           = 0;
//    const STATUS_ACTIVE             = 1;
//
//    const DEVICE_TYPE_IOS           = "ios";
//    const DEVICE_TYPE_ANDROID       = "android";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password', 'social_media_platform','gender','dob','role_id','status','phone','area','latitude','longitude','social_media_id','profile_picture', 'device_token','device_type','push_status','newsfeed_status','message_status','avaibility_status','prefix','year_of_experience','background','profession','token','promo_video','statistics'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//         'getCityName'
//    ];

	protected $casts = ['push_status' => 'integer','newsfeed_status' => 'integer','message_status' => 'integer','avaibility_status' => 'integer'];
    protected $visible = ['id', 'social_media_platform','full_name','email','gender','dob','role_id','status','phone', 'area','latitude','longitude','social_media_id','profile_picture','prefix','year_of_experience', 'profession','background','device_token','device_type','push_status','newsfeed_status','message_status', 'avaibility_status','created_at','updated_at','profile_image','qualifications','qualifications1','hospitals','categories','_token','average_rate','total_followers','is_like','promo_video','promo_file'];

    protected $appends = ['profile_image','average_rate','total_followers','is_like','promo_file'];

    public function getProfileImageAttribute() {

        $profileImage = asset(Config::get('constants.front.dir.getprofilePicPath') . ($this->profile_picture ?: Config::get('constants.front.default.profilePic')));
        return $profileImage;
    }
    public function getPromoFileAttribute() {

        $promoFile = asset(Config::get('constants.front.dir.getpromoVideoPath') . ($this->promo_video ?: Config::get('constants.front.default.promoVideo')));


        return $promoFile;
    }

	
    public function getIsLikeAttribute(){
        if(self::$likeUserId) {
            $instance = $this->FavoriteDetails()->getQuery()->where('user_id',self::$likeUserId)->first();
            return ($instance) ? 1 : 0;
        }
        return 0;
    }

    public function scopeWithIsLike($query, $user_id=0){
        self::$likeUserId = $user_id;
        return $query;
    }
	
    public function FavoriteDetail()
    {
        return $this->hasMany('App\Favorite','user_id');
    }

    public function FavoriteDetails()
    {
        return $this->hasMany('App\Favorite','doctor_id');
    }

    public function qualifications()
    {
        return $this->hasMany('App\Qualification','user_id');
    }
//    public function qualifications1()
//    {
//        return $this->belongsToMany('App\Category', 'user_categories', 'user_id', 'category_id');
//    }


    public function categories()
    {
//        return $this->hasMany('App\UserCategory','user_id');
        return $this->belongsToMany('App\Category', 'user_categories', 'user_id', 'category_id');
    }
    public function hospitals()
    {
        return $this->hasMany('App\Hospital','user_id');
    }

    public function hospital()
    {
        return $this->hasMany('App\Hospital','user_id');
    }

    public function usercategory()
    {
        return $this->hasMany('App\UserCategory','user_id');
    }

    public function isAdmin() {
        return (bool) (intval($this->attributes['role_id']) === self::ROLE_ADMIN);
    }
    public function getAverageRateAttribute() {

        $doctor_id  = isset($_REQUEST['doctor_id']) ? $_REQUEST['doctor_id'] : 0;
        $AverageRate = Review::where('doctor_id',$doctor_id)->avg('rate');
        return $AverageRate;
    }
    public function getTotalFollowersAttribute()
    {
        $doctor_id  = isset($_REQUEST['doctor_id']) ? $_REQUEST['doctor_id'] : 0;
        $totalfavoritecount = Favorite::where('doctor_id',$doctor_id)->count();
        return $totalfavoritecount;
    }

}
