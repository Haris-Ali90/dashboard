<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use Hash;
use Config;

use Gregwar\Image\Image;
use JWTAuth;

use App\Http\Requests\Frontend\UserRegisterRequest;
use App\Http\Requests\Frontend\CategoryRequest;
use App\Http\Requests\Frontend\EditProfileRequest;
use App\Http\Requests\Frontend\UpdateDeviceRequest;
use App\Helpers\RESTAPIHelper;

use Validator;
use App\Http\Requests\Frontend\UserRegisterRequest2;
use Illuminate\Support\Str;

use App\Setting;
use App\User;
use App\ContactUs;
use App\Report;
use App\Notification;
use App\City;
use App\Cms;
use App\Category;
use App\Service;
use App\Area;
use App\Zone;
use App\Ticket;
use App\ServiceQuestion;
use App\AdminHospitals;

class CategoryController extends ApiBaseController {
    
     //Return All Categories
    public function listCategories(Request $request)
    {
        
        $query=$request->all();
        
            $allCategories=Category::all();
            return RESTAPIHelper::response( $allCategories ,'Success','Categories');
       
    }
    public function zoneCategories(Request $request)
    {
        
        $query=$request->all();
        
            $allZone=Zone::all();
            return RESTAPIHelper::response( $allZone ,'Success','Zone');
       
    }
    public function areaCategories(Request $request)
    {
        
        $query=$request->all();
        
            $allArea=Area::all();
            return RESTAPIHelper::response( $allArea ,'Success','Area');
       
    }
     public function ticketCategories(Request $request)
    {
        
            
            $allTicket=Ticket::with('zone_name','area_name','product_name')->get();
            /*return view('view-tickets', ['allTicket' => $allTicket]);*/
            return RESTAPIHelper::response( $allTicket ,'Success','Ticket');
       
    }

    //Return with sub categories
    public function services(Request $request)
    {   
        $input             = $request->all();
        $category_id=$input['category_id'];
        $getServies = Service::where('category_id',$category_id)->get();
        return RESTAPIHelper::response( $getServies ,'Success','Services');
    }

public function allServices(Request $request)
    {   
        $input             = $request->all();
        $getServies = Service::all();
        return RESTAPIHelper::response( $getServies ,'Success','All Services');
    }
 
 public function featuredServices(Request $request)
    {
        $input = $request->all();
        if (!isset($input['user_id']))
        {
            return RESTAPIHelper::response('user_id is required', false);
        }
         
         $data = Service::where('is_featured',1)->get();
        
           
        return RESTAPIHelper::response( $data ,'Success','Featured services');
    }
     public function servicesQuestion(Request $request)
    {
        $input = $request->all();
        if (!isset($input['user_id']))
        {
            return RESTAPIHelper::response('user_id is required', false);
        }
        if (!isset($input['service_id']))
        {
            return RESTAPIHelper::response('service_id is required', false);
        }
         
         $data = ServiceQuestion::where('service_id',$input['service_id'])->get();
        
           
        return RESTAPIHelper::response( $data ,'Success','Services Question');
    }

    //Return specific category
    public function getCategories(Request $request)
    {
        $input             = $request->all();
        $parent_id=$input['parent_id'];

        $getSubCategories = Category::where('parent_id',$parent_id)->with('subcategory','subcategory.subcategory')->get();
        return RESTAPIHelper::response( $getSubCategories ,'Success','Categories');
    }

    public function allhospital(Request $request)
    {
        $input             = $request->all();
        $hospital=$input['hospital'];

        // Select * from hospitals where name like '%ph_%';
        $search=AdminHospitals::query()->where('hospital_name','like','%'.$hospital.'_%')->first();
        //$getSubCategories = Category::where('parent_id',$hospital)->with('subcategory','subcategory.subcategory')->get();
        return RESTAPIHelper::response( $search ,'Success','Hospitals');
    }

    public function getallhospital(Request $request)
    {
        $input             = $request->all();
        // Select * from hospitals where name like '%ph_%';
        $search=AdminHospitals::all();
        //$getSubCategories = Category::where('parent_id',$hospital)->with('subcategory','subcategory.subcategory')->get();
        return RESTAPIHelper::response( $search ,'Success','List of all Hospitals');
    }

    public function keywordCategories(Request $request)
    {
        $input             = $request->all();
        $category=$input['category'];
        // Select * from hospitals where name like '%ph_%';
        $search=Category::query()->where('title','like','%'.$category.'_%')->with('subcategory','subcategory.subcategory')->get();
        return RESTAPIHelper::response( $search ,'Success','Categories');
    }

    //Return Main Categories
    public function Categories(Request $request)
    {
        $query=$request->all();
        $lang=$query['lang'];
        //dd($lang);
        if(isset($query['lang']) && $query['lang']== "ar")
        {
            $Categories=Category::where('parent_id',0)->get(['id','title_ar','parent_id','created_at','updated_at']);
            //$Categories = Category::where('parent_id',0)->get();
            return RESTAPIHelper::response( $Categories ,'Success','Categories');
        }
        $Categories=Category::where('parent_id',0)->get(['id','title','parent_id','created_at','updated_at']);
        return RESTAPIHelper::response( $Categories ,'Success','Categories');
    }

    //Return Main Category Speciality
    public function Speciality(Request $request)
    {
        $query=$request->all();
        $lang=$query['lang'];
        if(isset($query['lang']) && $query['lang']== "ar")
        {
            $Speciality=Category::where('parent_id',$query['parent_id'])->get(['id','title_ar','parent_id','created_at','updated_at']);
            //return RESTAPIHelper::response( $Categories ,'Success','Categories');
            //$Speciality = Category::where('parent_id',$query['parent_id'])->get();
            return RESTAPIHelper::response( $Speciality ,'Success','Speciality');

        }
        $Speciality=Category::where('parent_id',$query['parent_id'])->get(['id','title','parent_id','created_at','updated_at']);
        return RESTAPIHelper::response( $Speciality ,'Success','Speciality');
    }

    //Return speciality Related Sub Speciality
    public function subSpeciality(Request $request)
    {
        $query=$request->all();
        $lang=$query['lang'];
        if(isset($query['lang'])=="ar")
        {
            $subSpeciality=Category::where('parent_id',$query['parent_id'])->get(['id','title_ar','parent_id','created_at','updated_at']);
            //$subSpeciality = Category::where('parent_id',$query['parent_id'])->get();
            return RESTAPIHelper::response( $subSpeciality ,'Success','Sub Speciality');

        }
        $subSpeciality=Category::where('parent_id',$query['parent_id'])->get(['id','title','parent_id','created_at','updated_at']);
        return RESTAPIHelper::response( $subSpeciality ,'Success','Sub Speciality');
    }

}
