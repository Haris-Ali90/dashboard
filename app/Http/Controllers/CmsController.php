<?php
namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use Hash;
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
use App\Setting;
use App\User;
use App\ContactUs;
use App\Report;
use App\Notification;
use App\City;
use App\Cms;
use App\Hospital;
use App\Qualification;
use App\UserCategory;
use App\Post;
use DB;

class CmsController extends ApiBaseController {

    public function cms(Request $request)
    {
        $query = $request->all();
        $languageValue = $query['lang'];
        if($languageValue=='ar')
        {
            $getCms = Cms::where('key',$query['keyword'])->select ('id', 'title_ar AS title','body_ar AS body')->first();
        }
        else
        {
            $getCms = Cms::where('key',$query['keyword'])->select ('id', 'title','body')->first();
        }

        return RESTAPIHelper::response( $getCms ,'Success','Cms');
    }

    public function about(Request $request)
    {
        $query = $request->all();
        $languageValue = $query['lang'];
        if($languageValue=='ar')
        {
            $getCms = Cms::where('key','about')->select ('id', 'title_ar AS title','body_ar AS body')->first();
        }
        else
        {
            $getCms = Cms::where('key','about')->select ('id', 'title','body')->first();
        }
        return RESTAPIHelper::response( $getCms ,'Success','About');
    }
    
}
