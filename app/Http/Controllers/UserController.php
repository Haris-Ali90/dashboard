<?php
namespace App\Http\Controllers;
use App\Category;
// use App\UserService;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use Hash;
use File;
use Config;
use Gregwar\Image\Image;
use JWTAuth;

use App\Http\Requests\Frontend\UserRegisterRequest;
use App\Http\Requests\Frontend\DoctorRegisterRequest;
use App\Http\Requests\Frontend\EditProfileRequest;
use App\Http\Requests\Frontend\UpdateDeviceRequest;
use App\Helpers\RESTAPIHelper;
use App\Http\Requests\Frontend\PatientRegisterRequest;
use App\Http\Requests\Frontend\DoctorProfileUpdateRequest;
use Validator;
use App\Http\Requests\Frontend\ChangePasswordRequest;
use App\Http\Requests\Frontend\UserRegisterRequest2;
use App\Http\Requests\Frontend\FacebookRequest;
use App\Http\Requests\Frontend\PatientFacebookRequest;
use Illuminate\Support\Str;
use App\Http\Requests\Frontend\PatientEditProfileRequest;
use GuzzleHttp\Client;
use App\Setting;
use App\User;
use App\ContactUs;
use App\Ticket;
use App\Report;
use App\Notification;
use App\City;
use App\Cms;
use App\Hospital;
use App\Qualification;
use App\UserCategory;
use App\Post;
use App\EducationType;
use App\UserDocuments;
use App\UserService;
use App\AddCart;
use App\Service;
use Session;
use Crypt;
use App\CartDetails;
use DB;


class UserController extends ApiBaseController {
    
    public function search(Request $request)
    {
       
        $userId = $request->input('user_id');
        
        $search = $request->input('keyword');
        // Category::where('product_name', 'like', "%$search%")
        $userObj = User::where('role_id', User::ROLE_USER)->get();
        $dev_msg = "Search Users";
        return RESTAPIHelper::response( $userObj ,'Success',$dev_msg);
        

    }
    
	public function aboutus()
    {    
       
	    $cms = Cms::where('keyword','aboutus')->first();
         $dev_msg = "About Us";
        return RESTAPIHelper::response( $cms ,'Success',$dev_msg);
    }
    public function help()
    {    
       
         $cms = Cms::where('keyword','help')->first();
         $dev_msg = "Help";
        return RESTAPIHelper::response( $cms ,'Success',$dev_msg);
    }
    public function privacyPolicy()
    {    
       
         $cms = Cms::where('keyword','privacypolicy')->first();
         $dev_msg = "Privacy Policy";
        return RESTAPIHelper::response( $cms ,'Success',$dev_msg);
    }
    public function termsConditions()
    {    
       
         $cms = Cms::where('keyword','terms&conditions')->first();
         $dev_msg = "Terms & Conditions";
        return RESTAPIHelper::response( $cms ,'Success',$dev_msg);
    }

    public function createContact(Request $request)
    {
        $input = $request->all();
        if (!isset($input['subject']))
        {
            return RESTAPIHelper::response('subject is required', false);
        }
        if (!isset($input['name']))
        {
            return RESTAPIHelper::response('name is required', false);
        }
        if (!isset($input['email']))
        {
            return RESTAPIHelper::response('email is required', false);
        }
        if (!isset($input['phone']))
        {
            return RESTAPIHelper::response('phone is required', false);
        }
        if (!isset($input['description']))
        {
            return RESTAPIHelper::response('description is required', false);
        }
        $results = ContactUs::create($input);      
        return RESTAPIHelper::response( $results ,'Success','Contact has been submited');
    }
    public function createTicket(Request $request)
    {
        $input = $request->all();
        if (!isset($input['zone_id']))
        {
            return RESTAPIHelper::response('Zone ID is required', false);
        }
        if (!isset($input['area_id']))
        {
            return RESTAPIHelper::response('Area ID is required', false);
        }
        if (!isset($input['category_id']))
        {
            return RESTAPIHelper::response('Category ID is required', false);
        }
        if (!isset($input['NIC_number']))
        {
            return RESTAPIHelper::response('NIC Number is required', false);
        }
        if (!isset($input['description']))
        {
            return RESTAPIHelper::response('Description is required', false);
        }
        if (!isset($input['address']))
        {
            return RESTAPIHelper::response('Address is required', false);
        }
        $results = Ticket::create($input);      
        return RESTAPIHelper::response( $results ,'Success','Tickets has been submited');
    }
    
    public function userServices(Request $request)
    {
        $input = $request->all();
        if (!isset($input['user_id']))
        {
            return RESTAPIHelper::response('user_id is required', false);
        }
        if (!isset($input['category_id']))
        {
            return RESTAPIHelper::response('category_id is required', false);
        }
        if (!isset($input['service_id']))
        {
            return RESTAPIHelper::response('service_id is required', false);
        }
        
        $results = UserService::create($input);      
        return RESTAPIHelper::response( $results ,'Success','User services has been saved');
    }
    
    public function userPasswordUpdate(Request $request)
    {
        $input = $request->all();
        if (!isset($input['email']))
        {
            return RESTAPIHelper::response('email is required', false);
        }
        if (!isset($input['password']))
        {
            return RESTAPIHelper::response('password is required', false);
        }
        
        $input['password'] = Hash::make($input['password']);
        $user = User::where('email',$input['email'])
          ->update(['password' => $input['password']]);
            
        return RESTAPIHelper::response( $user ,'Success','Password updated successfully');
    }
    
    public function deleteCart(Request $request)
    {
        $input = $request->all();
        if (!isset($input['cartId']))
        {
            return RESTAPIHelper::response('cartId is required', false);
        }
        
         $cart = AddCart::where('id',$input['cartId'])->delete();
            
        return RESTAPIHelper::response( $cart ,'Success','Item deleted successfully');
    }
    
    public function userServiceList(Request $request)
    {
        $input = $request->all();
        if (!isset($input['user_id']))
        {
            return RESTAPIHelper::response('user_id is required', false);
        }
         
         $data = UserService::where('user_id',$input['user_id'])->with('Services')->get();
        
           
        return RESTAPIHelper::response( $data ,'Success','User services');
    }
	 
	
	public function guestUserToken(Request $request)
    {
        $projectname            =   $request->input('project_name');
        $token                  =  base64_encode($projectname);

        $result['token']        = $token;

        return RESTAPIHelper::response( $result);
    }

    public function user_register(PatientRegisterRequest $request)
    {

        $input = $request->all();
        $input['email'] = ucwords($input['email']);
        /*$input['first_name']=ucwords($input['full_name']);*/
        $input['last_name']=ucwords($input['user_name']);
        $input['password'] = Hash::make($input['password']);
        $input['phone'] = ucwords($input['phone']);
        $input['address'] = ucwords($input['address']);
        $input['role_id']  = $input['role_id'];
        $input['status'] = 1;
        $userCreated = User::create($input);
        /* if ($request->hasFile('profile_picture')) {
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.profilePicPath'));
            $request->file('profile_picture')->move($path, $imageName);
            $input['profile_picture'] = $imageName;
            $request->profile_picture = $imageName;
            
        }
        else
        
        {
            $imageName="default.png";
            $input['profile_picture'] = $imageName;
            $request->profile_picture = $imageName;
        }*/
         $dev_msg = "Registered successfully";
         $userData = User::where('email',$input['email'])->get()->toArray();
         return RESTAPIHelper::response( $userData ,'Success',$dev_msg);
        //return $this->login($request,'1');
    }
    
    public function login(Request $request)
    {  
	    $input = $request->all();
        $user = User::where("email",$request->input('email'))->get();
        if(Crypt::decrypt($user[0]->password)==$request->input('password'))
        {
            $request->session()->put('users', $user[0]->name);
            return redirect('{{url('/')}}');
        }
     
        
        $userData = User::where('email',$input['email'])->get()->toArray();
        $dev_msg = "Login successfully";
        return RESTAPIHelper::response( $userData ,'Success',$dev_msg);

        
    } 


    public function remove_user(Request $request)
    {
        $input             = $request->all();
        $email=$input['email'];
        User::where('email',$email)->delete();

        return RESTAPIHelper::response('Success', 'Removed User Successfully');
    }

    public function user_login(Request $request,$is_register = '')
    {

        $input            = $request->only(['email','password']);


        $input['role_id'] = User::ROLE_USER;
        $input['status']  = 1;

        if (!$token = JWTAuth::attempt($input)) {
            
            return RESTAPIHelper::response('Invalid credentials, please try-again.', false);
        }

        $userData = JWTAuth::toUser($token)->toArray();

        $userData['_token'] = $token;


        if($is_register)
        {
            $dev_msg = "User register successfully";

        }
        else
        {
            $dev_msg = "User login successfully";
        }

        return RESTAPIHelper::response( $userData ,'Success',$dev_msg);
    }

    public function forgotPassword(Request $request) {

        if(!$request->input('email') )
        {
            return RESTAPIHelper::response('','Error','Email Missing', false);
        }

        $userRequested = User::where([
            'email'   =>  $request->input('email')
        ])->first();

        if ( !$userRequested )
            return RESTAPIHelper::response('','Error','Email not found in database.', false);

        //  $passwordGenerated = \Illuminate\Support\Str::random(12);

        // $userRequested->password = Hash::make( $passwordGenerated );
        //  $userRequested->save();

     // $emailBody = "You have requested to reset a password of your account, please find your new generated password below:

     //         New Password : " .$passwordGenerated. "

     //         Thanks.";
     //    \Mail::raw( $emailBody, function($m) use($userRequested) {
     //        $m->to( $userRequested->email )->from( env('MAIL_USERNAME') )->subject('Your password has been reset - Edulights');
     //    });

        return RESTAPIHelper::response( 'We have sent you password in your email, please check your inbox as well as spam/junk folder.' );
    }

    public function emailTemplate(Request $request)
    {
        if(!$request->input('email'))
        {
            return RESTAPIHelper::response('','Error','Email Missing', false);
        }
        $userRequested = User::where([
            'email'   =>  $request->input('email')
        ])->first();

        if ( !$userRequested )
            return RESTAPIHelper::response('Email not found in database.', false, 'invalid_email');        
        $data = ['student' => $userRequested,'course'=>$course ];
        \Mail::send('feerecipt' , $data, function($m) use($userRequested)
        {
         // $m->attachData($pdf, 'invoice.pdf', ['mime' => 'application/pdf']);
         $m->to($userRequested->email)->from(env('MAIL_USERNAME'))->subject('Student Fee Recipt - Edulights');
        });
        return RESTAPIHelper::response( 'We have sent you password in your email, please check your inbox as well as spam/junk folder.' );
    }

    public function doctorregister(DoctorRegisterRequest $request)
    {
        $input             = $request->all();
        $input['full_name']=ucwords($input['full_name']);
        $input['background']=ucwords($input['background']);
        $input['qualification']=ucwords($input['qualification']);
        $input['hospital']=ucwords($input['hospital']);

        $user=User::where('email',$input['email'])->first();
        if($user)
        {
            return RESTAPIHelper::response(array(),'false','Email already found in our system, please try another one.');
        }
            

        
        $input['password'] = Hash::make($input['password']);
        $input['role_id']  = User::Doctor_Role;
        $input['status']  =1;


        if ($request->hasFile('profile_picture')) {
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.profilePicPath'));
            $request->file('profile_picture')->move($path, $imageName);

            //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
            $input['profile_picture'] = $imageName;
            $request->profile_picture = $imageName;
            // }
        }
        else{
            $imageName="default.png";
            $input['profile_picture'] = $imageName;
            $request->profile_picture = $imageName;
        }

        $userCreated = User::create($input);
        $lastid=$userCreated->id;
        $hospital=explode(",",$input['hospital']);
        Hospital::where('user_id', $lastid)->delete();
        foreach($hospital as &$value) {
            Hospital::create(['title' => $value, 'user_id' => $lastid]);
        }
        $qualification=explode(",",$input['qualification']);
        Qualification::where('user_id', $lastid)->delete();
        foreach($qualification as &$value) {
            Qualification::create(['title' => $value, 'user_id' =>$lastid,'doctor_id' =>$lastid]);
        }
        UserCategory::where('user_id', $lastid)->delete();
        $checkCategory=Category::where('id',$input['category_id'])->first();

        if(!$checkCategory)
        {
            return RESTAPIHelper::response('','Error','Following category does not exist', false);
        }
        UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['category_id']]);
        if(isset($input['speciality_id'])&& $input['speciality_id']>0)
        {
            UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['speciality_id']]);
        }
        if(isset($input['sub_speciality_id'])&& $input['sub_speciality_id']>0)
        {
            UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['sub_speciality_id']]);
        }


        $dev_msg = "User Register successfully";
        $getSubCategories = User::where('id',$lastid)->with('qualifications')->with('categories')->with('hospitals')->get();
        return RESTAPIHelper::response( $getSubCategories ,'Success',$dev_msg);

        return $this->login($request,'1');
    }

   

    public function getDoctorProfile(Request $request)
    {
        $doctor_id=$request->doctor_id;
        if(!$doctor_id){

            return RESTAPIHelper::response('','Error','doctor_id missing', false);
        }
        //$userData = User::where('id',$request->doctor_id)->get()->first();
        $userData = User::where('id',$request->doctor_id)->where('role_id',1)->with('qualifications')->with('categories')->with('hospitals')->first();
        $userData['fileURL']    =   asset('/')."api/download/video?doctor_id=".$userData['id'];
        //dd($userData);
        if (!$userData){
            return RESTAPIHelper::response( '','Error','Enter valid doctor Id', false);
        }
        else {
            return RESTAPIHelper::response( $userData ,'Success','Doctor profile');

        }

    }

    public function updateDoctor(DoctorProfileUpdateRequest $request)
    {

        $input             = $request->all();
        $input['full_name']=ucwords($input['full_name']);
        $input['background']=ucwords($input['background']);
        $input['qualification']=ucwords($input['qualification']);
        $input['hospital']=ucwords($input['hospital']);
        $is_authorized      = $this->checkTokenValidity($input['user_id']);

        if($is_authorized == 0)
        {
            return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        }

        $user   = $this->getUserInstance();

        if($user) {

            if ($request->hasFile('profile_picture')) {
                $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $path = public_path(Config::get('constants.front.dir.profilePicPath'));
                $request->file('profile_picture')->move($path, $imageName);

                //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                $input['profile_picture'] = $imageName;
                // }
                   }
		if ($request->hasFile('promo_video')) {

            $videoName = Str::random(12) . '.' . $request->file('promo_video')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.promoVideoPath'));
			#echo $path;die();
            $request->file('promo_video')->move($path, $videoName);
			$video = $path.$videoName;
			$thumbnail = str_replace("mp4","jpg",$video);
			shell_exec("C:\\ffmpeg\\bin\\ffmpeg.exe -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");

            #if (Image::open($path ."Original/" . $videoName)->scaleResize(400)->save($path . '/Thumb/' . $videoName)) {}
			#if (Image::open($path ."Original/" . $videoName)->scaleResize(200)->save($path . '/Tiny/' . $videoName)) {}
                $input['promo_video'] = $videoName;
            	$input['file_type'] = 'video';
                $data=explode("/",$thumbnail);
                $input['thumbnail_image'] = $data[2];
        }
		/*		   
        if ($request->hasFile('promo_video')) {
            $videoName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('promo_video')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.promoVideoPath'));
            $request->file('promo_video')->move($path, $videoName);
            //$path1= public_path(Config::get('constants.front.dir.videoThumbnailPath'));
            $input['promo_video'] = $videoName;
            $input['file_type'] = 'video';
           // echo $path;
            //$video = $path.$videoName;
            //echo $video;

            //$video = '../../public/images/promo_video/'.$videoName;
            //$thumbnail = '../../public/images/thumbnail/img.jpg';

            //$video = $_SERVER['DOCUMENT_ROOT'].'/hospital_care/public/images/promo_video/'.$videoName;
            //$thumbnail = $_SERVER['DOCUMENT_ROOT'].'/hospital_care/public/images/thumbnail/img.jpg';

//                 echo $video;
//                echo  $thumbnail;




            //$thumbnail = str_replace("mp4","jpg",$video);
            //echo $thumbnail;
            //$thumbnail_name = str_replace("mp4","jpg",$videoName);
            //echo $thumbnail_name;
            //$input['thumbnail_image'] = $thumbnail_name;
            //echo $thumbnail;
//            shell_exec("C:\\ffmpeg\\bin\\ffmpeg.exe -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");
            //echo $check;
        }
		*/

            $hospital=explode(",",ucwords($input['hospital']));
            // dd($hospital);
            Hospital::where('user_id', $input['user_id'])->delete();
            foreach($hospital as &$value) {
                Hospital::create(['title' => $value, 'user_id' => $input['user_id']]);
            }
            $qualification=explode(",",$input['qualification']);
            // dd($hospital);
            Qualification::where('user_id', $input['user_id'])->delete();
            foreach($qualification as &$value) {
                Qualification::create(['doctor_id' => $input['user_id'],'title' => $value, 'user_id' => $input['user_id']]);
            }
//            $category=explode(",",$input['category_id']);
            // dd($hospital);
            UserCategory::where('user_id', $input['user_id'])->delete();
//            foreach($category as &$value) {
            $checkCategory=Category::where('id',$input['category_id'])->first();

            if(!$checkCategory)
            {
                return RESTAPIHelper::response('','Error','Following category does not exist', false);
            }
            UserCategory::create(['doctor_id' => $input['user_id'],'user_id' => $input['user_id'], 'category_id' => $input['category_id']]);
            if(isset($input['speciality_id'])&& $input['speciality_id']>0)
            {
                UserCategory::create(['doctor_id' => $input['user_id'],'user_id' => $input['user_id'], 'category_id' => $input['speciality_id']]);
            }
            if(isset($input['sub_speciality_id'])&& $input['sub_speciality_id']>0)
            {
                UserCategory::create(['doctor_id' => $input['user_id'],'user_id' => $input['user_id'], 'category_id' => $input['sub_speciality_id']]);
            }
//            }
            $user->update($input);
            $dev_msg = "Profile updated successfully";
            $getSubCategories = User::where('id',$input['user_id'])->with('qualifications')->with('categories')->with('hospitals')->get();
//            exit($getSubCategories[0]->qualifications1()->toSql());
            return RESTAPIHelper::response( $getSubCategories ,'Success',$dev_msg);
        }
        else
        {
            // if no user object found .....
            return RESTAPIHelper::response('no user found for the given id', false);
        }

    }

    public function updateUser(PatientEditProfileRequest $request)
    {
        $input             = $request->all();
        
        if(!isset($input['user_id'])) {
            return RESTAPIHelper::response('','Error','user_id is required', false);
        }
        $user_id = $input['user_id'];
        $user = User::where('id',$user_id)->first();

        // dd($user);
        if($user) 
        {

            if ($request->hasFile('profile_picture'))
            {
               // dd("come");
                $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $path = public_path(Config::get('constants.front.dir.profilePicPath'));
                $request->file('profile_picture')->move($path, $imageName);
                           // dd($imageName);
                $input['profile_picture'] = $imageName;

            }
               

            $user->update($input);
       
            $dev_msg = "User Profile updated successfully";
            return RESTAPIHelper::response( $user ,'Success',$dev_msg);
        } 
        else
        {
            // if no user object found .....
            return RESTAPIHelper::response('no user found for the given id', false);
        }

    }
public function updateServices(Request $request)
    {
        $input = $request->all();
                if(!isset($input['user_id'])) {
            return RESTAPIHelper::response('','Error','user_id is required', false);
        }
        if(!isset($input['category_id'])) {
            return RESTAPIHelper::response('','Error','category_id is required', false);
        }
        if(!isset($input['service_id'])) {
            return RESTAPIHelper::response('','Error','service_id is required', false);
        }
        $user_id = $input['user_id'];
        $user = User::where('id',$user_id)->first();

        // dd($user);
        if($user) 
        {
             UserCategory::create(array('user_id' => $input['user_id'],'category_id',$input['category_id'],'service_id',$input['service_id']));
       
            $dev_msg = "Profile services updated successfully";
            return RESTAPIHelper::response( new stdClass(),'Success',$dev_msg);
        } 
        else
        {
            // if no user object found .....
            return RESTAPIHelper::response('no user found for the given id', false);
        }

    }

    public function getUserProfile(Request $request)
    {
        echo "string";

    }
    public function getEducation(Request $request)
    {
        // if(!$request->user_id)
        // {

        //     return RESTAPIHelper::response('','Error','user_id missing', false);
        // }

        $userData = EducationType::all();
        // $userData = User::where('id',$request->user_id)->get()->first();
        if($userData){
            return RESTAPIHelper::response( $userData ,'Success','Education Type List');
        }
        else
        {
            return RESTAPIHelper::response('','Error','User Not Exist', false);
        }


    }

    public function verifyEmailAddress(Request $request) {

        if(!$request->input('verification_code') )
        {
            return RESTAPIHelper::response('','Error','Parameter Missing', false);
        }

        $userRequested = User::where([
            'verification_code'   =>  $request->input('verification_code')
        ])->first();

        if ( !$userRequested )
            return RESTAPIHelper::response('Code not found in database.', false, 'invalid_code');


        return RESTAPIHelper::response( $userRequested ,'Success','Code verified successfully');
    }

    public function resetPassword(Request $request) {

        if(!$request->input('password') || !$request->input('user_id'))
        {
            return RESTAPIHelper::response('','Error','Parameter Missing', false);
        }

        $userRequested = User::where([
            'id'   =>  $request->input('user_id')
        ])->first();


        $passwordGenerated = \Illuminate\Support\Str::random(12);

        $userRequested->password = Hash::make( $passwordGenerated );
        $userRequested->save();

        return RESTAPIHelper::response( $userRequested ,'Success','Password reset successfully');
    }

    public function changePassword(ChangePasswordRequest $request) {
                      
        $input  = $request->all();
        // if(!$request->has('user_id')) {
        //     return RESTAPIHelper::response('','Error','Please insert user id', false);
        // }
        // if(!$request->has('password')) {
        //     return RESTAPIHelper::response('','Error','Please insert user id', false);
        // }
        // if(!$request->has('old_password')) {
        //     return RESTAPIHelper::response('','Error','Please insert user id', false);
        // }
        if ($request->has('password') && $request->get('password', '') !== '') {
            
            $userRequested = User::where([
                'id'   =>  $request->input('user_id')
            ])->first();
            $loginattemp['email']   =  $userRequested->email;
            $loginattemp['password'] =  $request->get('old_password');

            
            if (Hash::check($request->get('old_password'), $userRequested['password'])==false) {
                
                // dd("come");
              return RESTAPIHelper::response('Old password is not valid', false);
                   }
                //   dd("out");
            // if (!$token = JWTAuth::attempt($loginattemp)) {
            //     return RESTAPIHelper::response('Old password is not valid', false);
            // }

            $dataToUpdate['password'] = \Hash::make($request->get('password'));
            $userRequested->password = $dataToUpdate['password'];
            $userRequested->save();
        }

        return RESTAPIHelper::response( $userRequested ,'Success','Password changed successfully');
    }
    public function doctorFacebookLogin(FacebookRequest $request) {


        $input = $request->all();

        if (!isset($input['social_media_id']) || trim($input['social_media_id']) == '') {
            return RESTAPIHelper::response(array(), 'Error', 'Please Enter Social ID');
        } elseif (!isset($input['social_media_platform']) || trim($input['social_media_platform']) == '') {
            return RESTAPIHelper::response(array(), 'Error', 'Please Enter Social Platform');
        }
//        elseif (!isset($input['device_type']) || trim($input['device_type']) == '') {
//            return RESTAPIHelper::response(array(), 'Error', 'Please Enter device_type');
//        } elseif (!isset($input['device_token']) || trim($input['device_token']) == '') {
//            return RESTAPIHelper::response(array(), 'Error', 'Please Enter device_token');
//        }

        $userData = User::where('social_media_id', $input['social_media_id'])->first();

        if (!empty($userData)) {
//            if ($request->hasFile('profile_picture')) {
//                $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
//                $path = public_path(Config::get('constants.front.dir.profilePicPath'));
//                $request->file('profile_picture')->move($path, $imageName);
//
//                //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
//                $input['profile_picture'] = $imageName;
//                $request->profile_picture = $imageName;
//                // }
//            }
//            else{
//                $imageName="default.png";
//                $input['profile_picture'] = $imageName;
//                $request->profile_picture = $imageName;
//            }
//            $userData->update($input);
//            $lastid=$userData->id;
//            $hospital=explode(",",$input['hospital']);
//            Hospital::where('user_id', $lastid)->delete();
//            foreach($hospital as &$value) {
//                Hospital::create(['title' => $value, 'user_id' => $lastid]);
//            }
//            $qualification=explode(",",$input['qualification']);
//            Qualification::where('user_id', $lastid)->delete();
//            foreach($qualification as &$value) {
//                Qualification::create(['title' => $value, 'user_id' =>$lastid,'doctor_id' =>$lastid]);
//            }
//            UserCategory::where('user_id', $lastid)->delete();
//            $checkCategory=Category::where('id',$input['category_id'])->first();
//
//            if(!$checkCategory)
//            {
//                return RESTAPIHelper::response('','Error','Following category does not exist', false);
//            }
//            UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['category_id']]);
//            $getSubCategories = User::where('id',$lastid)->with('qualifications')->with('categories')->with('hospitals')->get();
//            $token = '';
//            $token = JWTAuth::fromUser($userData);
//           // $getSubCategories = $getSubCategories->toArray();
//            $getSubCategories['_token'] = $token;
//            return RESTAPIHelper::response($getSubCategories, 'Success', 'Logged In Successfully');
            return RESTAPIHelper::response(array(), 'Error', 'Facebook User Exist please log In');
        }
        else
        {
            $check_email=User::where('email',$input['email'])->first();
            if($check_email) {
                if ($request->hasFile('profile_picture')) {
                    $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                    $path = public_path(Config::get('constants.front.dir.profilePicPath'));
                    $request->file('profile_picture')->move($path, $imageName);

                    //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                    // }
                }
                else{
                    $imageName="default.png";
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                }
                $check_email->update($input);
                $lastid=$check_email->id;
                $hospital=explode(",",$input['hospital']);
                Hospital::where('user_id', $lastid)->delete();
                foreach($hospital as &$value) {
                    Hospital::create(['title' => $value, 'user_id' => $lastid]);
                }
                $qualification=explode(",",$input['qualification']);
                Qualification::where('user_id', $lastid)->delete();
                foreach($qualification as &$value) {
                    Qualification::create(['title' => $value, 'user_id' =>$lastid,'doctor_id' =>$lastid]);
                }
                UserCategory::where('user_id', $lastid)->delete();
                $checkCategory=Category::where('id',$input['category_id'])->first();

                if(!$checkCategory)
                {
                    return RESTAPIHelper::response('','Error','Following category does not exist', false);
                }
                UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['category_id']]);
                if(isset($input['speciality_id'])&& $input['speciality_id']>0)
                {
                    UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['speciality_id']]);
                }
                if(isset($input['sub_speciality_id'])&& $input['sub_speciality_id']>0)
                {
                    UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['sub_speciality_id']]);
                }
                $msg = "User Updated Successfully";
                $getSubCategories = User::where('id',$lastid)->with('qualifications')->with('categories')->with('hospitals')->get();
                $token = '';
                $token = JWTAuth::fromUser($check_email);
               // $getSubCategories = $getSubCategories->toArray();
                $getSubCategories[0]['_token'] = $token;
                return RESTAPIHelper::response($getSubCategories[0], 'Success',$msg);
            }
            else
            {
                $input['role_id']  = User::Doctor_Role;
                $input['status']  =1;
                if ($request->hasFile('profile_picture')) {
                    $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                    $path = public_path(Config::get('constants.front.dir.profilePicPath'));
                    $request->file('profile_picture')->move($path, $imageName);

                    //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                    // }
                }
                else{
                    $imageName="default.png";
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                }
                $check_email = User::create($input);
                $lastid=$check_email->id;
                $hospital=explode(",",$input['hospital']);
                Hospital::where('user_id', $lastid)->delete();
                foreach($hospital as &$value) {
                    Hospital::create(['title' => $value, 'user_id' => $lastid]);
                }
                $qualification=explode(",",$input['qualification']);
                Qualification::where('user_id', $lastid)->delete();
                foreach($qualification as &$value) {
                    Qualification::create(['title' => $value, 'user_id' =>$lastid,'doctor_id' =>$lastid]);
                }
                UserCategory::where('user_id', $lastid)->delete();
                $checkCategory=Category::where('id',$input['category_id'])->first();

                if(!$checkCategory)
                {
                    return RESTAPIHelper::response('','Error','Following category does not exist', false);
                }
                UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['category_id']]);
                if(isset($input['speciality_id'])&& $input['speciality_id']>0)
                {
                    UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['speciality_id']]);
                }
                if(isset($input['sub_speciality_id'])&& $input['sub_speciality_id']>0)
                {
                    UserCategory::create(['doctor_id' => $lastid,'user_id' => $lastid, 'category_id' => $input['sub_speciality_id']]);
                }
                $msg = 'Register Successfully';
                $getSubCategories = User::where('id',$lastid)->with('qualifications')->with('categories')->with('hospitals')->get();
                $token = '';
                $token = JWTAuth::fromUser($check_email);
               // $getSubCategories = $getSubCategories->toArray();
                $getSubCategories[0]['_token'] = $token;
                return RESTAPIHelper::response($getSubCategories[0], 'Success',$msg);
            }
            $token = '';
            $token = JWTAuth::fromUser($check_email);
            $userData = $check_email->toArray();
            $userData['_token'] = $token;
            return RESTAPIHelper::response($userData, 'Success',$msg);
        }
    }

    public function doctorLoginFacebook(Request $request) {
        $input = $request->all();

        if (!isset($input['social_media_id']) || trim($input['social_media_id']) == '') {
//            return RESTAPIHelper::response(array(), 'Error', 'Please Enter Social ID');
            return RESTAPIHelper::response('','Error','Please Enter Social ID', false);
        }
        $userData = User::where('social_media_id', $input['social_media_id'])->first();

        if (!empty($userData)) {


            $userData->update($input);
            $lastid=$userData->id;
            $userData = User::where('id', $lastid)->first();

            $getSubCategories = User::where('id',$lastid)->with('qualifications')->with('categories')->with('hospitals')->get();
            $token = '';
            $token = JWTAuth::fromUser($userData);
            $getSubCategories = $getSubCategories->toArray();
            $getSubCategories[0]['_token'] = $token;
            return RESTAPIHelper::response($getSubCategories[0], 'Success', 'Logged In Successfully');

        }
        else
        {
           // return RESTAPIHelper::response(array(), 'Error', 'Facebook User not Exit');
            return RESTAPIHelper::response('','Error','Facebook User not Exit', false);

        }
    }
    
    public function patientFacebookLogin(PatientFacebookRequest $request) {

        $input = $request->all();

        if (!isset($input['social_media_id']) || trim($input['social_media_id']) == '') {
            return RESTAPIHelper::response(array(), 'Error', 'Please Enter Social ID');
        } elseif (!isset($input['social_media_platform']) || trim($input['social_media_platform']) == '') {
            return RESTAPIHelper::response(array(), 'Error', 'Please Enter Social Platform');
        }
        $userData = User::where('social_media_id', $input['social_media_id'])->first();

        if (!empty($userData)) {
//            $userData->update($input);
//            $token = '';
//            $token = JWTAuth::fromUser($userData);
//           // $userData = $userData->toArray();
//            $userData['_token'] = $token;
//            return RESTAPIHelper::response($userData, 'Success', 'Logged In Successfully');

            return RESTAPIHelper::response(array(), 'Error', 'Facebook User Exist please log In');
        }
        else
        {
            $check_email=User::where('email',$input['email'])->first();
            if($check_email) {
                if ($request->hasFile('profile_picture')) {
                    $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                    $path = public_path(Config::get('constants.front.dir.profilePicPath'));
                    $request->file('profile_picture')->move($path, $imageName);

                    //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                    // }
                }
                else{
                    $imageName="default.png";
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                }
                $check_email->update($input);
                $msg = "User Updated Successfully";
            }
            else
            {
                //$input['email'] = $input['social_media_id']. "@" . $input['social_media_platform'] . ".com";
//                $input['password'] = Hash::make($input['password']);
                $input['role_id']  = User::Patient_Role;
                $input['status']  =1;
                if ($request->hasFile('profile_picture')) {
                    $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                    $path = public_path(Config::get('constants.front.dir.profilePicPath'));
                    $request->file('profile_picture')->move($path, $imageName);

                    //if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                    // }
                }
                else{
                    $imageName="default.png";
                    $input['profile_picture'] = $imageName;
                    $request->profile_picture = $imageName;
                }
                $check_email = User::create($input);
                $msg = 'Register Successfully';
            }

            $token = '';
            $token = JWTAuth::fromUser($check_email);
            $userData = $check_email->toArray();
            $userData['_token'] = $token;
            return RESTAPIHelper::response($userData, 'Success',$msg);
        }

    }

    public function patientLoginFacebook(Request $request) {

        $input = $request->all();

        if (!isset($input['social_media_id']) || trim($input['social_media_id']) == '') {
            return RESTAPIHelper::response(array(), 'Error', 'Please Enter Social ID');
        }
        $userData = User::where('social_media_id', $input['social_media_id'])->first();

        if (!empty($userData)) {
            $userData->update($input);
            $token = '';
            $token = JWTAuth::fromUser($userData);
            $userData = $userData->toArray();
            $userData['_token'] = $token;
            return RESTAPIHelper::response($userData, 'Success', 'Logged In Successfully');

        }
        else
        {
            return RESTAPIHelper::response(new \stdClass(), 'Error', 'Facebook User Not Exist');
        }

    }

//    public function doctorSearch(Request $request)
//    {
//        $offset = $request->input('offset');
//        $offset = isset($offset) ? $offset : 0;
//        $limit = $request->input('limit');
//        $limit = isset($limit) ? $limit : 10;
//
//        $userIds = $uids = $hospital_uIds = $phy_uIds = array();
//
//        $userId = $request->input('user_id');
//        $userId = isset($userId) ? $userId : 0;
//        //  $userIds                = array();
//        $search = $request->input('search');
//        $longitude = $request->input('longitude');
//        $latitude = $request->input('latitude');
//        $hospital             = $request->input('hospital');
//        $physician_id          = $request->input('physician_id');
//        $status          = $request->input('status');
//
//        $is_authorized = $this->checkTokenValidity($userId);
//        if ($is_authorized == 0) {
//            return RESTAPIHelper::response('', 'Error', 'Invalid Token or User Id', false);
//        }
//
//        $doctorObj = User::where('role_id', User::ROLE_DOCTOR);
//
//        if (isset($search)) {
//            $doctorObj = $doctorObj->where('avaibility_status', $status );
//            //   dd($doctorObj);
//        }
//        if (isset($search)) {
//            $doctorObj = $doctorObj->where(function ($query) use ($search) {
//                $query->where('full_name', 'like', "%$search%")->orwhere('id', 'like', "%$search%");
//            });
//            //   dd($doctorObj);
//        }
//
//
//        if (isset($latitude) && isset($longitude)) {
//            // $uids = array();
//            $uIds = DB::select("SELECT us.id, (3959 * ACOS( COS( RADIANS( " . $latitude . " ) ) * COS( RADIANS( `latitude` ) ) * COS(RADIANS( `longitude` )
//                            - RADIANS( " . $longitude . " )) + SIN(RADIANS(" . $latitude . ")) * SIN(RADIANS(`latitude`)))) `distance` FROM users us
//                             where us.`role_id` = 1
//                            HAVING `distance` < 100
//                            ORDER BY  us.id ASC");
//
//            if ($uIds) {
//                foreach ($uIds as $uObj) {
//                    $uids[] = $uObj->id;
//                }
//            }
//
//        }
//        if (isset($hospital)) {
//
//            $hospital_uIds = Hospital::where('title','like',"%$hospital%")->pluck('user_id')->toArray();
//
//        }
//
//        if (isset($physician_id)) {
//
//            $phy_uIds                = UserCategory::where('category_id',$physician_id)->pluck('user_id')->toArray();
//
//        }
//
//        if (!empty($hospital_uIds))
//        {
//            if (!empty($phy_uIds))
//            {
//                if (!empty($uids))
//                {
//                    $userIds    = array_intersect($uids,$hospital_uIds,$phy_uIds);
//
//                }
//                else {
//                    $userIds = array_intersect($hospital_uIds, $phy_uIds);
//                }
//            }
//            elseif (!empty($uids))
//            {
//                $userIds    = array_intersect($uids,$hospital_uIds);
//
//            }
//            else
//            {
//                $userIds    = $hospital_uIds;
//            }
//        }
//        elseif (!empty($phy_uIds))
//        {
//            if (!empty($uids))
//            {
//                $userIds    = array_intersect($uids,$phy_uIds);
//            }
//            else {
//                $userIds =  $phy_uIds;
//            }
//        }
//        else
//        {
//
//            $userIds =  $uids;
//
//        }
//        //dd($userIds);
//        if(isset($hospital) || isset($physician_id) || isset($uids)) {
//            $doctorObj  = $doctorObj->whereIn('id',$userIds);
//        }
//
//
//        $totalRecords = $doctorObj->count();
//        $postObj = $doctorObj->offset($offset)
//            ->limit($limit)
//            ->get();
//        //  dd($postObj);
//        return RESTAPIHelper::response($postObj,'Success', 'Data retrieved successfully');
//    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate( $this->extractToken() );

        return RESTAPIHelper::emptyResponse();
    }

    public function ContactUs(Request $request)
    {
        $input             = $request->all();
        $is_authorized      = $this->checkTokenValidity($input['user_id']);

        if($is_authorized == 0) {
            return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        }

        if(!$input['user_id']) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }

        if(!$input['text']) {
            return RESTAPIHelper::response('','Error','Please insert text', false);
        }

        ContactUs::create($input);

        $dev_msg = "We've received your message";
        return RESTAPIHelper::response( new \stdClass(),'Success',$dev_msg);

    }
    
    public function add_to_cart(Request $request)
    {
        $input  = $request->all();
        
        if(!$input['user_id']) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }

        if(!$input['service_id']) {
            return RESTAPIHelper::response('','Error','Please insert service_id', false);
        }
         if(!$input['quantity']) {
            return RESTAPIHelper::response('','Error','Please insert quantity', false);
        }
        
        $itemCount = AddCart::where('user_id',$input['user_id'])->get();
        if(count($itemCount)>0)
        {
            AddCart::where('user_id',$input['user_id'])->delete();
        }  
        $quantities = explode(",",$input['quantity']);
        $items = explode(",",$input['service_id']);
        
        for($i=0; $i < count($quantities);$i++)
        {
            AddCart::create(['user_id'=>$input['user_id'],'quantity'=>$quantities[$i],'service_id'=>$items[$i]]);
        }
        

        $dev_msg = "added your cart";
        return RESTAPIHelper::response( new \stdClass(),'Success',$dev_msg);

    }
    public function cart_details(Request $request)
    { 
         $input  = $request->all();
        if(!$input['user_id'])
        {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }
        $cartDetails = AddCart::where('user_id',$input['user_id'])->with('Service')->get();
        
        if($cartDetails){
            return RESTAPIHelper::response( $cartDetails ,'Success','Cart Details');
        }
        else
        {
            return RESTAPIHelper::response('','Error','User Not Exist', false);
        }


    }
    public function confirm_order(Request $request)
    {
        $input  = $request->all();
        
        if(!$input['user_id']) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }
        if(!$input['delivery_name']) {
            return RESTAPIHelper::response('','Error','Please insert delivery_name', false);
        }
        if(!$input['delivery_phone']) {
            return RESTAPIHelper::response('','Error','Please insert delivery_phone', false);
        }
        //  if(!$input['delivery_email']) {
        //     return RESTAPIHelper::response('','Error','Please insert delivery_email', false);
        // }
         if(!$input['delivery_address']) {
            return RESTAPIHelper::response('','Error','Please insert delivery_address', false);
        }
        if(!$input['requirements']) {
            return RESTAPIHelper::response('','Error','Please insert requirements', false);
        }
        $sub_total = 0;
        $cartItems = AddCart::where('user_id',$input['user_id'])->get();
        foreach($cartItems as $records)
        {   
            if($records->service_id>0){
                
            $service = Service::where('id',$records->service_id)->first();
            $sub_total += $service->price*$records->quantity;
            }
        }
        
        $input['sub_total']=$sub_total;
       
        CartDetails::create($input);
        $inputUser['phone'] = $input['delivery_phone'];
        $inputUser['full_name'] = $input['delivery_name'];
        $inputUser['address'] = $input['delivery_address'];
        $inputUser['role_id'] = 0;
        $password = Hash::make('mart12345');
        $inputUser['password'] = $password;
        
        $userCreated = User::create($inputUser); 
        $dev_msg = "order Placed";
        return RESTAPIHelper::response( new \stdClass(),'Success',$dev_msg);

    }

    public function updateDeviceToken(Request $request) {
        
        $input             = $request->all();
        $userId            = $input['user_id'];

        // $is_authorized = $this->checkTokenValidity($userId);
        // if($is_authorized == 0) {
        //     return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        // }
        if(!$request->has('user_id')) {
            return RESTAPIHelper::response('','Error','Please insert user id', false);
        }
        if(!$request->has('device_type')) {
            return RESTAPIHelper::response('','Error','Please insert device type', false);
        }
        if(!$request->has('device_token')) {
            return RESTAPIHelper::response('','Error','Please insert device token', false);
        }
        $olduser = User::where([
            'device_token'   =>  $request->input('device_token')
        ])->first();
        if ($olduser){
            $datachange= array_filter([
                'device_token' => ' '
            ]);
            $olduser->update($datachange);
        }

        $user = User::find($userId);

        $dataToUpdate = array_filter([
            'id' => $request->get('user_id', null),
            'device_type' => $request->get('device_type', null),
            'device_token' => $request->get('device_token', null)
        ]);

        $user->update($dataToUpdate);

        return RESTAPIHelper::response(new \stdClass(),'Success','Device token updated successfully', false);

    }

    public function getCities(Request $request)
    {
        $resultArray = array();
        $getCities = City::get();
        $resultArray['Cities'] = $getCities;
        return RESTAPIHelper::response( $resultArray ,'Success','Cities list');
    }

    public function chnageNotificationStatus(Request $request) {

        $input             = $request->all();
        $is_authorized      = $this->checkTokenValidity($request->input('user_id'));

        if($is_authorized == 0) {
            return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        }

        if(!$request->has('user_id')) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }

        if(!$request->has('notification_status')) {
            return RESTAPIHelper::response('','Error','Please insert notification status', false);
        }

        $userInfo = User::where([
            'id'   =>  $request->input('user_id')
        ])->first();

        $userInfo->update($input);

        $dev_msg = "Notification status changed successfully";
        return RESTAPIHelper::response( $userInfo ,'Success',$dev_msg);

    }

    public function about(Request $request)
    {
        $getCms = Cms::where('key','about')->first();
        return RESTAPIHelper::response( $getCms ,'Success','About');
    }

    public function getNotifications(Request $request)
    {
        $input  = $request->all();

        if(!isset($input['user_id']))
        {
            return RESTAPIHelper::response('','Error','user_id is required', false);
        }

        // $getNotification = Notification::with('ReceiverDetail')->where('reciever_id',$input['user_id'])->orderBy('created_at', 'DESC')->get();
        $getNotification = Notification::where('reciever_id',$input['user_id'])->orWhere('sender_id',$input['user_id'])->with('ReceiverDetail')
        ->orderBy('created_at', 'DESC')->get();
        return RESTAPIHelper::response( $getNotification ,'Success','Notification list');
    }
    public function getNotificationsCount(Request $request)
    {
        $input  = $request->all();

        if(!isset($input['user_id']))
        {
            return RESTAPIHelper::response('','Error','user_id is required', false);
        }
        $getNotification = Notification::where('reciever_id',$input['user_id'])->with('ReceiverDetail')
        ->orderBy('created_at', 'DESC')->get();


        return RESTAPIHelper::response( $getNotification ,'Success','Notification list');
    }

    public function chnagePushNotificationStatus(Request $request) {

        $input             = $request->all();
        

        if(!$request->has('user_id')) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }

        if(!$request->has('notification_status')) {
            return RESTAPIHelper::response('','Error','Please insert push notification status', false);
        }
        $userInfo = User::where([
            'id'   =>  $request->input('user_id')
        ])->first();

        $userInfo->update($input);
		$userInfo = $userInfo->toArray();
        $userInfo['document_images'] = UserDocuments::where('user_id',$request->input('user_id'))->get();
        $dev_msg = "Push Notification status changed successfully";
        return RESTAPIHelper::response( $userInfo ,'Success',$dev_msg);

    }

    public function chnageNewsfeedNotificationStatus(Request $request) {

        $input             = $request->all();
        $is_authorized      = $this->checkTokenValidity($request->input('user_id'));

        if($is_authorized == 0) {
            return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        }

        if(!$request->has('user_id')) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }


        if(!$request->has('newsfeed_status')) {
            return RESTAPIHelper::response('','Error','Please insert newsfeed notification status', false);
        }
        $userInfo = User::where([
            'id'   =>  $request->input('user_id')
        ])->first();
        $userInfo->update($input);

        $dev_msg = "Newsfeed Notification status changed successfully";
        return RESTAPIHelper::response( $userInfo ,'Success',$dev_msg);

    }

    public function chnageMessageNotificationStatus(Request $request) {

        $input             = $request->all();
        $is_authorized      = $this->checkTokenValidity($request->input('user_id'));

        if($is_authorized == 0) {
            return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        }

        if(!$request->has('user_id')) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }

        if(!$request->has('message_status')) {
            return RESTAPIHelper::response('','Error','Please message insert notification status', false);
        }

        $userInfo = User::where([
            'id'   =>  $request->input('user_id')
        ])->first();
        $userInfo->update($input);



        $dev_msg = "Message Notification status changed successfully";
        return RESTAPIHelper::response( $userInfo ,'Success',$dev_msg);

    }

    public function chnageAvaibilityNotificationStatus(Request $request) {

        $input             = $request->all();
        $is_authorized      = $this->checkTokenValidity($request->input('user_id'));

        if($is_authorized == 0) {
            return RESTAPIHelper::response('','Error','Invalid Token or User Id', false);
        }

        if(!$request->has('user_id')) {
            return RESTAPIHelper::response('','Error','Please insert user_id', false);
        }

        if(!$request->has('avaibility_status')) {
            return RESTAPIHelper::response('','Error','Please insert avaibility notification status', false);
        }

        $userInfo = User::where([
            'id'   =>  $request->input('user_id')
        ])->first();
        $userInfo->update($input);


        $dev_msg = "Avaibility Notification status changed successfully";
        return RESTAPIHelper::response( $userInfo ,'Success',$dev_msg);

    }

    public function testpush(Request $request)
    {
        $input             = $request->all();

 	if(!$request->has('device_token')) {
            return RESTAPIHelper::response('','Error','Please pass device token', false);
        }

	$device = $input['device_token'];
	$message = "This is test mesage dev my_occasion";
    $title = "Title";
	$postArray = array('alert'=>$message,'sound'=>'default','title'=>$title,'message'=>$message);
	$response = $this->sendPushNotificationAndroid($device,$postArray);
       // dd($response);
	return $response;



}

    public function doctorSearch(Request $request)
    {
        $offset = $request->input('offset');
        $offset = isset($offset) ? $offset : 0;
        $limit = $request->input('limit');
        $limit = isset($limit) ? $limit : 10;

        $userIds = $uids = $hospital_uIds = $phy_uIds = array();

        $userId = $request->input('user_id');
        $userId = isset($userId) ? $userId : 0;
// $userIds = array();
        $search = $request->input('search');
        $longitude = $request->input('longitude');
        $latitude = $request->input('latitude');
        $hospital = $request->input('hospital');
        $physician_id = $request->input('physician_id');
        $status = $request->input('status');
$distance = $request->input('distance');
        $distance = isset($distance) ? $distance : 60;
        $is_authorized = $this->checkTokenValidity($userId);
        if ($is_authorized == 0) {
            return RESTAPIHelper::response('', 'Error', 'Invalid Token or User Id', false);
        }

        $doctorObj = User::where('role_id', User::ROLE_DOCTOR);

        if (isset($status)) {
            $doctorObj = $doctorObj->where('avaibility_status', $status );
// dd($doctorObj);
        }
        if (isset($search)) {
            $doctorObj = $doctorObj->where(function ($query) use ($search) {
               $query->where('full_name', 'like', "%$search%")->orWhere('id', 'like', "%$search%");
            });
            
 //dd($doctorObj);
        }

        if (isset($latitude) && isset($longitude) && isset($distance)) {
// $uids = array();
            $uIds = DB::select("SELECT us.id, (3959 * ACOS( COS( RADIANS( " . $latitude . " ) ) * COS( RADIANS( `latitude` ) ) * COS(RADIANS( `longitude` )
- RADIANS( " . $longitude . " )) + SIN(RADIANS(" . $latitude . ")) * SIN(RADIANS(`latitude`)))) `distance` FROM users us
where us.`role_id` = 1
HAVING `distance` < " .$distance. "
ORDER BY us.id ASC");

            if(!$uIds)
            {
                //return RESTAPIHelper::response('Not found', 'Error', false);
                return RESTAPIHelper::response(array(), 'Success', 'No Record Found');
            }
            if ($uIds) {
                foreach ($uIds as $uObj) {
                    $uids[] = $uObj->id;
                }
            }

        }
        if (isset($hospital)) {

            $hospital_uIds = Hospital::where('title','like','%'.$hospital.'%')->pluck('user_id')->toArray();
           // dd($hospital_uIds);
            if(!$hospital_uIds)
            {

                return RESTAPIHelper::response('Not found', 'Error', false);
            }
        }

        if (isset($physician_id)) {

            $phy_uIds = UserCategory::where('category_id',$physician_id)->pluck('user_id')->toArray();
            if(!$phy_uIds)
            {

                return RESTAPIHelper::response('Not found', 'Error', false);
            }

        }

        $searchQuery = false;
        if(count($hospital_uIds)>0 && count($phy_uIds)>0 && count($uids)>0)
        {
            $searchQuery = true;
            $userIds= array_intersect($uids,$hospital_uIds,$phy_uIds);
//            $userIds = array_intersect($uids,$firstIds);
        }
        else if(count($hospital_uIds)>0 && count($phy_uIds)>0) {
            $searchQuery = true;
            $userIds = array_intersect($hospital_uIds,$phy_uIds);
        }
        else if(count($phy_uIds)>0 && count($uids)>0) {
            $searchQuery = true;
            $userIds = array_intersect($uids,$phy_uIds);
        }
        else if(count($hospital_uIds)>0 && count($uids)>0) {
            $searchQuery = true;
            $userIds = array_intersect($hospital_uIds,$uids);
        }
        else if(count($hospital_uIds)>0) {
            $searchQuery = true;
            $userIds = $hospital_uIds;
        }
        else if(count($phy_uIds)>0) {
            $searchQuery = true;
            $userIds = $phy_uIds;
        }
        else if(count($uids)>0) {
            $searchQuery = true;
            $userIds = $uids;
        }


//
//        if (!empty($hospital_uIds))
//        {
//            if (!empty($phy_uIds))
//            {
//                if (!empty($uids))
//                {
//                    $firstIds= array_intersect($uids,$hospital_uIds,$phy_uIds);
//                    $userIds = array_intersect($uids,$firstIds);
//                }
//                else {
//                    $userIds = array_intersect($hospital_uIds, $phy_uIds);
//                }
//            }
//            elseif (!empty($uids))
//            {
//                $userIds = array_intersect($uids,$hospital_uIds);
//            }
//            else
//            {
//                $userIds = $hospital_uIds;
//                //dd($userIds);
//            }
//        }
//        elseif (!empty($phy_uIds))
//        {
//            if (!empty($uids))
//            {
//                $userIds = array_intersect($uids,$phy_uIds);
//            }
//            else {
//                $userIds = $phy_uIds;
//            }
//        }
//        elseif(!empty($uids))
//        {
//          $userIds = $uids;
//        }
       // dd($userIds);
        if($userIds) {

            $doctorObj = $doctorObj->whereIn('id', $userIds);
            //$totalRecords = $doctorObj->count();
            $postObj = $doctorObj->withIsLike($userId)->with('qualifications')->with('categories')->with('hospitals')->offset($offset)->limit($limit)->get();

                return RESTAPIHelper::response($postObj, 'Success', 'Data retrieved successfully');


        } else if ($searchQuery)
        {
            return RESTAPIHelper::response('Not found', 'Error', false);
        } else {
            $postObj = $doctorObj->withIsLike($userId)->with('qualifications')->with('categories')->with('hospitals')->offset($offset)
                ->limit($limit)
                ->get();
            //dd($postObj);
            if (count($postObj) == 0) {
                //return RESTAPIHelper::response('No Record Found', 'Error', false);
                return RESTAPIHelper::response($postObj, 'Success', 'No Record Found');
            } else {

                return RESTAPIHelper::response($postObj, 'Success', 'Data retrieved successfully');

            }
        }

    }

    public function changeStatus(Request $request,$userId)
    {
        header('Content-type: application/json');
        $allNotificationsFromDB = DB::table('users')->where('id', $userId)->get();

        $allNotificationsFromDB	=	(array)$allNotificationsFromDB;

        $currentStatus	=	$allNotificationsFromDB[0]->status;
        if($currentStatus==0)
        {
            DB::table('users')->where('id', $userId)->update(['status' => 1]);
        }
        else
        {	DB::table('users')->where('id', $userId)->update(['status' => 0]);	}

        echo $currentStatus;
    }

    public function shareVideo(Request $request)
    {
        $query=$request->all();
        if(!$query['latitude'])
        {
            return RESTAPIHelper::response('','Error','latitude is required', false);
        }
        if(!$query['longitude'])
        {
            return RESTAPIHelper::response('','Error','longitude is required', false);
        }
        $getUser = User::where('id',$query['user_id'])->first();
        if(!$getUser)
        {
            return RESTAPIHelper::response('','Error','User not Exist', false);
        }
        if(!$getUser['promo_video'])
        {
            return RESTAPIHelper::response('','Error','Upload Promo video for sharing', false);
        }
        $data['user_id']=$query['user_id'];
        $data['latitude']=$query['latitude'];
        $data['longitude']=$query['longitude'];
        $data['profile_video']=$getUser->promo_video;
        $data['thumbnail_image']=$getUser->thumbnail_image;
        $data['file_type']=$getUser->file_type;
        //Post::where('user_id',$query['user_id'])->update(['profile_video' => $getUser->promo_video]);
        Post::create($data);
        return RESTAPIHelper::response( $getUser ,'Success','Video Shared Successfully');
    }

    public function testthumb(Request $request)
    {
        //$video = $_SERVER['DOCUMENT_ROOT'].'/hospital_care/public/images/promo_video/53V8MLBXmlpa.mp4';
       echo  $video  = public_path() . '\images\promo_video\53V8MLBXmlpa.mp4';
        //$thumbnail = '../../public/images/thumbnail/img.jpg';

        //$video = $_SERVER['DOCUMENT_ROOT'].'/hospital_care/public/images/promo_video/'.$videoName;
       // $thumbnail = $_SERVER['DOCUMENT_ROOT'].'/hospital_care/public/images/promo_video/img.jpg';
       echo $thumbnail = public_path() . '\images\promo_video\img.jpg';


       //$check=shell_exec("ffmpeg -i $video -vf scale=800:356 -r 25/1 -q:v 1 $thumbnail");
        $check=shell_exec("C:\\ffmpeg\\bin\\ffmpeg.exe -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");
        dd($check);
    }

    public function downloadVideo(Request $request)
    {
        $input = $request->all();
        //dd($input['doctor_id']);
        $video = User::where('id',$input['doctor_id'])->pluck('promo_video');
       // dd($video[0]);
        $file = $video[0];
        $fileURL    =   asset('public/' . Config::get('constants.front.dir.promoVideoPath'). ($file ?: Config::get('constants.front.default.promoVideoPath')));
        #echo $fileURL;die();

        //dd($file);
       /* if (file_exists($fileURL)) { }
        else
        {echo "not";}*/


            #dd("exist");
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($fileURL).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileURL));
            readfile($fileURL);
            exit;


    }

    private function sendRequest($method,$url,$data=NULL){
        
       
        $query = http_build_query($data);
        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_POSTFIELDS,$query);
        curl_setopt($process, CURLOPT_POST, 1);
        $return = curl_exec($process);
      //  return $return;
    }
    
    public function emailcheck(Request $request)
    {
        $input             = $request->all();
        $user = User::where('email',$input['email'])->first();
       if ($user)
       {
           return RESTAPIHelper::response('','Error','Email Already Exist', false);
       }

        return RESTAPIHelper::response( $user ,'Success','Email Not Exist');
    }
    
    public function signup(Request $request){
        
        $input = $request->all();
        $input['country'] = "CA";
        
        $url = 'https://digital101.net/jugadoo/api/register';
        $query = http_build_query($input);
        $process = curl_init();
        curl_setopt($process,CURLOPT_URL,$url);
        curl_setopt($process, CURLOPT_POSTFIELDS,$query);
        curl_setopt($process, CURLOPT_POST, 1);
        $return = curl_exec($process);
        
       // $response =  $this->sendRequest('POST','https://digital101.net/jugadoo/api/register',$input);
        dd($return->Response);
    }

}
