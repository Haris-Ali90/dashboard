<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\UserUpdateRequest;
use App\Http\Requests\Frontend\ChangePasswordRequest;
use Config;

use Illuminate\Http\Request;

// use App\Http\Requests;
use App\Http\Requests\Backend\UserRequest;
use App\Http\Requests\Backend\ChangepwdRequest;
use Illuminate\Support\Facades\Request as FacadeRequest;
use App\Http\Controllers\Backend\BackendController;
//use Validator;
use App\Http\Requests\Frontend\UserRegisterRequest;

use App\Http\Requests\Frontend\EditProfileRequest;
use App\User;
use App\City;
use App\Cms;
use App\Post;
use Hash;
use App\EducationType;
use App\UserDocuments;
class CityController extends BackendController
{
    /**
     * Get Cities
     */
    public function getIndex()
    { 
        $cities = City::where(['role_id' => User::ROLE_USER])->get();
        return backend_view( 'city.index', compact('cities') );
    }

    /**
     * Show City edit form
     */
    public function edit(City $city)
    {
        return backend_view( 'city.edit', compact('city') );
    }

    /**
     * Show City add form
     */
    public function add()
    {
        return backend_view( 'city.add');
    }

    /**
     * Create City
     */
    public function create(Request $request,City $city)
    {
        $postData = $request->all();
        $city->create( $postData );

        session()->flash('alert-success', 'City has been created successfully!');
        return redirect( 'backend/cities');

    }

    /**
     * Update City
     */
    public function update(Request $request, City $city)
    {
        if ( $user->isAdmin() )
            abort(404);

        $postData = $request->all();

        $user->update( $postData );

        session()->flash('alert-success', 'City has been updated successfully!');
        return redirect( 'backend/cities');
    }

    /**
     * Delete City
     */
    public function destroy(City $city)
    {
        if ( $user->isAdmin() )
            abort(404);
        $userId = $city->id;
        $data = $city->delete();
	
        session()->flash('alert-success', 'City has been deleted successfully!');
        return redirect( 'backend/cities' );
    }

    /**
     * Get  City
     */
    public function profile($id)
    {
        $users        = User::where(['id' => $id])->get();
        $users = $users[0];
        
        return backend_view( 'users.profile', compact('users' ) );
    }

    /**
     * Show Change Password form
     */
    public function getChangePwd()
    {
        return backend_view( 'changepwd');
    }

    /**
     * Change Password
     */
    public function changepwd(ChangepwdRequest $request)
    {

        $postData = $request->all();

        $password=$postData['old_pwd'];
        //dd($password);
        $admin=User::where('email','admin@gmail.com')->first();
        $hashpwd=$admin['password'];
        if (Hash::check($password, $hashpwd))
        {
//            dd($password=$postData['new_pwd']);
            if ( $request->has('new_pwd') && $request->get('new_pwd', '') != '' ) {
                $postData['new_pwd'] = \Hash::make( $postData['new_pwd'] );
                $newpwd=$postData['new_pwd'];
                User::where('email','admin@gmail.com')->update(['password' => $newpwd]);
                session()->flash('alert-success', 'Password has been change successfully!');
                return redirect( 'backend/dashboard');
            }
        }
        else{

            session()->flash('alert-danger', 'Old password not Match!');
            return redirect( 'backend/changepwd');
        }
    }

    /**
     * Change Status
     */
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



}
