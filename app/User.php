<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;
use App\Review;
use Illuminate\Support\Facades\Mail;
class User extends Authenticatable
{
    protected $table = 'dashboard_users';
    use SoftDeletes;
//
	 public static $likeUserId = 0;
     const ROLE_ADMIN                = 2;
     const ROLE_USER                 = 0;
     const ROLE_SERVICE_PROVIDER     = 1;
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
        'is_email','is_scan','creator_id','type', 'id','full_name','role_type','user_name','email', 'address','password','city','country','role_id','status','phone','device_token','device_type','push_status','profile_picture','is_verify','verify_token','rights','permissions','token','statistics'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//         'getCityName'
//    ];

	protected $casts = ['role_id' => 'integer','status' => 'integer','push_status' => 'integer','is_verify' => 'integer'];

    protected $visible = ['id','full_name','user_name','email','city','country','role_id','phone','device_token','device_type','profile_picture','profile_image','address'];

    protected $appends = ['profile_image'];

    public function getProfileImageAttribute() {

        $profileImage = asset(Config::get('constants.front.dir.getprofilePicPath') . ($this->profile_picture ?: Config::get('constants.front.default.profilePic')));
        return $profileImage;
    }
    public function getThumbImageAttribute() {

        $thumbImage = asset(Config::get('constants.front.dir.getthumbnailVideoPath') . ($this->thumbnail_image ?: Config::get('constants.front.default.profilePic')));
        return $thumbImage;
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
	
    public function DocumentImages()
    {
        return $this->hasMany('App\UserDocuments','user_id');
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

    public function deactivate()
    {
        $this->status  = 0;
        $this->save();
    }

    public function activate()
    {
        $this->status  = 1;
        $this->save();
    }

    public function getPermissions()
    {
        return $this->Permissions->pluck('route_name')->toArray();
    }

    public function Role()
    {
        return $this->belongsTo(Roles::class, 'role_type','id');
    }

    public function Permissions()
    {
        return $this->hasMany(Permissions::class, 'role_id','role_type')->where('is_delete', 0);
    }

    public function PermissionsExtract()
    {
        return $this->hasMany(Permissions::class, 'role_id','role_type')->where('is_delete', 0)->pluck('route_name')->toArray();
    }


//    public function getStatusTextFormattedAttribute()
//    {
//        return (int)$this->attributes['status'] === 1 ?
//            '<a href="'. route('sub-admin.inactive', $this->attributes['id']) .'"><button type="button" class="btn  btn-xs status_change" style="background: #449d44; color: white;
//    border: 1px solid #169F85;">Active</button></a>' :
//            '<a href="'. route('sub-admin.active', $this->attributes['id']) .'"><button type="button" class="btn btn-danger btn-xs status_change">Blocked</button></a>';
//    }


	
	
public function sendPasswordResetEmail($email, $full_name, $token, $role_id)
    {
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
		$style1 = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';";
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/images/abc.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="'.$style.'">Hi, ' . $full_name . '!</h1>
             
                <p style="'.$style.'">You are receiving this email because we received a password reset request for your account.</p>
                <div style="text-align: center;'.$style.'"><a class="btn btn-link" href=' . route('password.reset', [$email, $token, $role_id]) . ' class="btn btn-primary" ><button style="background-color: #E36D28;border: 0px;border-radius: 6px;">Reset Password</button></a></div>
                 <p style="'.$style.'">If you did not request a password reset, no further action is required.</p>
                <br/>
                <p style="'.$style.'"> If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                <a style="word-break: break-all; '.$style1.'" href=' . route('password.reset', [$email, $token, $role_id]) . ' > '.route("password.reset", [$email, $token, $role_id]). '</a></p>
                <br/>
                <br/>
                 
               
                </div>
                 <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;'.$style.'">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;'.$style.'">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;'.$style.'">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
        $subject = "Reset Password Link";
        $email = base64_decode($email);
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }


    public function sendSubadminPasswordResetEmail($email, $full_name, $token, $role_id)
    {
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
		$style1 = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';";
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/images/abc.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="'.$style.'">Hi, ' . $full_name . '!</h1>
             
                <p style="'.$style.'">You are receiving this email because Joeyco Dashboard Admin has created your account for using Joeyco Dashboard, kindly reset your password and login to your account.</p>
                <div style="text-align: center;'.$style.'"><a class="btn btn-link" href=' . route('password.reset', [$email, $token, $role_id]) . ' class="btn btn-primary" ><button style="background-color: #E36D28;border: 0px;border-radius: 6px;">Reset Password</button></a></div>
                 <p style="'.$style.'">If you did not request for account, no further action is required.</p>
                
                 <br/>
                 <p style="'.$style.'"> If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                <a style="word-break: break-all;'.$style1.'" href=' . route('password.reset', [$email, $token, $role_id]) . ' > '.route("password.reset", [$email, $token, $role_id]). '</a></p>
                 <br/>
                 <br/>
                 
                </div>
                 <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;'.$style.'">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;'.$style.'">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;'.$style.'">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
        $subject = "Reset Password Link";
        $email = base64_decode($email);
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }


//    public function getStatusTextFormattedAttribute()
//    {
//        return (int)$this->attributes['status'] === 1 ?
//            '<a href="'. route('sub-admin.inactive', $this->attributes['id']) .'"><button type="button" class="btn  btn-xs status_change" style="background: #449d44; color: white;
//    border: 1px solid #169F85;">Active</button></a>' :
//            '<a href="'. route('sub-admin.active', $this->attributes['id']) .'"><button type="button" class="btn btn-danger btn-xs status_change">Blocked</button></a>';
//    }

    public function sendWelcomeEmail($randomid)
    {
        $email = $this->attributes['email'];
        $full_name = $this->attributes['full_name'];
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/images/abc.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="'.$style.'">Hi, ' . $full_name . '!</h1>
            
                 <p style="'.$style.'">You are receiving this email because we received a Two-factor authentication request for your account.</p>
                <p style="'.$style.'">Your Two-factor authentication code is <span style="background-color: #E36D28;border: 0px;">' . $randomid . '</span></p>
				<br/>
                 <p style="'.$style.'">If you did not request a Two-factor authentication, no further action is required.</p>
                  <br/>
               
               
                </div>
                <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;'.$style.'">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;'.$style.'">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;'.$style.'">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
        $subject = "Your 6 digit code for Authentication";
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }

    public function sendApproveAddressEmail($reattemptUser)
    {
        $email = $this->attributes['email'];
        $full_name = $this->attributes['full_name'];
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/images/abc.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="'.$style.'">Hi, ' . $full_name . '!</h1>
            
                 <p style="'.$style.'">Order Address/Phone-no has been updated by customer support, Please create reattempt!</p>
                 <h4 style="'.$style.'">Order Detail</h4>
                    <p style="'.$style.'">Tracking Id: <span style="background-color: #E36D28;border: 0px;">'.$reattemptUser->tracking_id.'</span></p>
				    <br/>
                    <p style="'.$style.'">Customer Address: <span style="background-color: #E36D28;border: 0px;">'.$reattemptUser->customer_address.'</span></p>
                    <br/>
                    <p style="'.$style.'">Customer Phone: <span style="background-color: #E36D28;border: 0px;">'.$reattemptUser->customer_phone.'</span></p>
                    </br>
                </div>
                <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;'.$style.'">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;'.$style.'">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;'.$style.'">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
        $subject = "Order Approval";
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }



}
