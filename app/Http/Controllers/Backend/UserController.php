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
class UserController extends BackendController
{
    public function getIndex()
    {
        $users = User::where(['role_id' => User::ROLE_USER])->get();
        
         // dd($users->toArray());
        return backend_view( 'users.index', compact('users') );
    }
	
	public function termsConditions()
    {     
	    $cms = Cms::where('key','terms')->first();

        return backend_view( 'users.terms', compact('cms'));
    }

    public function edit(User $user)
    {

        return backend_view( 'users.edit', compact('user') );
    }

    public function add()
    {
    
        return backend_view( 'users.add');
    }

    public function create(Request $request,User $user)
    {
        $postData = $request->all();
        // dd($postData);
        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $postData['password'] = \Hash::make( $postData['password'] );
        }

        if ($request->hasFile('profile_picture')) {
//            dd("dd");
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.profilePicPath'));
            $request->file('profile_picture')->move($path, $imageName);

            $postData['profile_picture'] = $imageName;
        }
        else{
//            dd("else");
            $imageName="default.png";
            $postData['profile_picture'] = $imageName;
            //  $request->profile_picture = $imageName;
        }
        $postData['role_id'] = User::ROLE_USER;
        $postData['status'] = 1;


        $user->create( $postData );

        session()->flash('alert-success', 'donor has been created successfully!');
        return redirect( 'backend/donor');

    }

    public function update(Request $request, User $user)
    {
        if ( $user->isAdmin() )
            abort(404);

        $postData = $request->all();

        if( $request->has('new_password') && $request->get('new_password', '') != '' ) {
            $postData['password'] = \Hash::make( $postData['new_password'] );
        }

        if($file = $request->hasFile('profile_picture')) {

            $file = $request->file('profile_picture') ;

            $fileName = $user->id . '-' . \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            echo $destinationPath = public_path().'/images/profile_images' ;
            $file->move($destinationPath,$fileName);
            $postData['profile_picture'] = $fileName ;
        }

        // dd($request);
        $user->update( $postData );

        session()->flash('alert-success', 'donor has been updated successfully!');
        return redirect( 'backend/donor');
    }

    public function destroy(User $user)
    {
        if ( $user->isAdmin() )
            abort(404);
        $userId = $user->id;
        $data = $user->delete();
	    // Post::where('user_id',$userId)->delete();
        session()->flash('alert-success', 'donor has been deleted successfully!');
        return redirect( 'backend/donor' );
    }

    public function profile($id)
    {
        $users        = User::where(['id' => $id])->get();
        $users = $users[0];
        
        return backend_view( 'users.profile', compact('users' ) );
    }

    public function getChangePwd()
    {
        return backend_view( 'changepwd');
    }
    
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
