<?php

namespace App\Http\Controllers\Backend;

use Config;
use Gregwar\Image\Image;

use Illuminate\Http\Request;

// use App\Http\Requests;
use App\Http\Requests\Backend\AdminRequest;
use Illuminate\Support\Facades\Request as FacadeRequest;
use App\Http\Controllers\Backend\BackendController;
//use Validator;

use App\Admin;
use App\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends BackendController
{
    /**
     * Get  profiles
     */
    public function getIndex()
    {
        $admins = Admin::where(['role_id' => 1])->where('is_subadmin' , '1')->get();
        return backend_view( 'admins.index', compact('admins') );
    }

    /**
     * Edit admin profile form
     */
    public function edit(Admin $admin)
    {
        return backend_view( 'admins.edit', compact('admin') );
    }

    /**
     * Get new profile form
     */
    public function add()
    {
        return backend_view( 'admins.add' );
    }

    /**
     * Create new profile
     */
    public function create(AdminRequest $request,Admin $admin)
    {
        $postData = $request->all();

        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $postData['password'] = \Hash::make( $postData['password'] );
        }

        $postData['role_id'] = 1;
        $postData['is_subadmin'] = '1';

        $adminRightsArray	 =	$postData['rights'];
        $adminRightsString	=	implode(",",$adminRightsArray);

        $postData['rights']	=	$adminRightsString;


        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $postData['password'] = \Hash::make( $postData['password'] );
        }


        if ($request->hasFile('profile_picture')) {
            $imageName = Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.profilePicPath'));
            $request->file('profile_picture')->move($path, $imageName);

            if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                $input['profile_picture'] = $imageName;
                $postData['profile_picture'] = $imageName;
            }
        }
        


        $admin->create( $postData );

        session()->flash('alert-success', 'Admin has been created successfully!');
        return redirect( 'backend/admin/add/' . $admin->id );

    }

    /**
     * update admin profile
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        $postData = $request->all();

        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $postData['password'] = \Hash::make( $postData['password'] );
        }


        if ($request->hasFile('profile_picture')) {
          echo   $imageName = Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
           echo  $path = public_path(Config::get('constants.front.dir.profilePicPath'));
            $request->file('profile_picture')->move($path, $imageName);
            if (Image::open($path . '/' . $imageName)->scaleResize(200, 200)->save($path . '/' . $imageName)) {
                $input['profile_picture'] = $imageName;
                $postData['profile_picture'] = $imageName;
            }
        }


        $adminRightsArray	 =	$postData['rights'];

         $adminRightsString	=	implode(",",$adminRightsArray);


        //print_r($adminRightsString);die();
        $postData['rights']	=	$adminRightsString;

        $admin->update( $postData );

        #print_r($postData);die();
        $postData['rights']	=	$adminRightsString;

        session()->flash('alert-success', 'Admin has been updated successfully!');
        return redirect( 'backend/admin/edit/' . $admin->id );
    }

    /**
     * Delete admin profile
     */
    public function destroy(Admin $admin)
    {

        $admin->delete();

        session()->flash('alert-success', 'Admin has been deleted successfully!');
        return redirect( 'backend/admin' );
    }

    /**
     * Get admin profile
     */
    public function profile($id)
    {
        $admins        = Admin::where(['role_id' => Admin::ROLE_MEMBER])->where(['id' => $id])->first()->toArray();
        $adminLogs     = Log::with(['admin'])->where(['log_generator' => $id])->get()->toArray();

        return backend_view( 'admins.profile', compact('admins' ,'adminLogs') );
    }
}
