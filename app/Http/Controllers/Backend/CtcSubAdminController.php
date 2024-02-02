<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\StoreSubadminRequest;
use App\Permissions;
use App\Roles;
use Illuminate\Http\Request;
use App\User;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Backend\ChangepwdRequest;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;

class CtcSubAdminController extends BackendController
{
    use ResetsPasswords;

    /**
     * Get Ctc Sub admins
     */
    public function getIndex()
    {
        
        return backend_view('ctc-subadmin.index');
    }

    /**
     * Yajra call after Ctc Sub admin
     */
    public function subAdminList(Datatables $datatables, Request $request)
    {
        $query = User::where(['role_id' => User::ROLE_ADMIN])->where('creator_id','=',Auth::user()->id)->where('email','!=',Auth::user()->email);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status', static function ($record) {
                return backend_view('ctc-subadmin.status', compact('record') );
            })
            ->editColumn('profile_picture', static function ($record) {
                if (isset($record->profile_picture)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->profile_picture . '" />';
                } else {
                    return '';
                }
            })
            ->addColumn('action', static function ($record) {
                
                return backend_view('ctc-subadmin.action', compact('record'));
            })
            ->make(true);
    }

    /**
     * Show Ctc Sub admin add form
     */
    public function add(Roles $role)
    {
        return backend_view( 'ctc-subadmin.add' );
        
    }

    /**
     * Create  Ctc Sub admin
     */
    public function create(StoreSubadminRequest $request,User $user)
    {
        $postData = $request->all();

        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $postData['password'] = \Hash::make( $postData['password'] );
        }

         if ($request->hasFile('profile_picture')) {
             $imageName = \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
             $path = public_path(Config::get('constants.front.dir.profilePicPath'));

             $request->file('profile_picture')->move($path, $imageName);
             $postData['profile_picture'] = url('/').'/images/profile_images/'.$imageName;
         }
         else{
             $imageName="default.png";
             $postData['profile_picture'] = url('/').'/images/profile_images/'.$imageName;
         }

        $postData['role_type'] = 50;
        $postData['role_id'] = User::ROLE_ADMIN;
        $postData['status'] = 1;
        $postData['creator_id'] = Auth::user()->id;

        $user->create( $postData );



        session()->flash('alert-success', 'Ctc sub admin has been created successfully!');

        //config(['auth.passwords.users.email' => 'backend.emails.password']);
        //$this->sendResetLinkEmail($request);
		$token = hash('ripemd160',uniqid(rand(),true));
        DB::table('password_resets')
            ->insert(['email'=> $postData['email'],'role_id' =>  User::ROLE_ADMIN,'token' => $token]);

        $email = base64_encode ($postData['email']);
        $user->sendSubadminPasswordResetEmail($email,$postData['full_name'],$token,User::ROLE_ADMIN);

        return redirect( 'ctc/subadmins' . $user->id );

    }

    /**
     * Show  Ctc Sub admin edit form
     */
    public function edit($id)
    {
        $sub_id = base64_decode($id);
        $user = User::find($sub_id);
        return backend_view( 'ctc-subadmin.edit', compact('user') );
    }

    /**
     * Update   Ctc Sub admin
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'full_name'  => 'required|max:255',
            'email'      => 'required|email|max:255',
        ]);
        $postData = $request->all();
        $updateRecord = [
            'full_name' => $postData['full_name'],
            'email' => $postData['email'],
            'phone' => $postData['phone'],
            'address' => $postData['address'],
        ];
        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $updateRecord['password'] = \Hash::make( $postData['password'] );
        }

        if($file = $request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture') ;

            $imageName = $user->id . '-' . \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $path = public_path().'/images/profile_images' ;

            $file->move($path,$imageName);
            $updateRecord['profile_picture'] = url('/').'/images/profile_images/'.$imageName ;
        }

        $user->update( $updateRecord );

        session()->flash('alert-success', 'Ctc sub admin has been updated successfully!');
        return redirect( 'ctc/subadmins');
    }

    /**
     * Delete  Ctc Sub admin
     */
    public function destroy(User $user)
    {
         $user->delete();
        session()->flash('alert-success', 'Ctc sub admin has been deleted successfully!');
        return redirect( 'ctc/subadmins' );
    }

    /**
     * get  Ctc Sub admin
     */
    public function profile($id)
    {
        $sub_id = base64_decode($id);
        $users        = User::where(['id' => $sub_id])->get();
        $users = $users[0];
        return backend_view( 'ctc-subadmin.profile', compact('users') );
    }

    /**
     * Active  Ctc Sub admin
     */
    public function active(User $record)
    {
        $record->activate();
        return redirect()
            ->route('ctc-subadmin.index')
            ->with('success', 'Ctc sub admin has been Active successfully!');
    }

    /**
     * Inactive  Ctc Sub admin
     */
    public function inactive(User $record)
    {

        $record->deactivate();
        return redirect()
            ->route('ctc-subadmin.index')
            ->with('success', 'Ctc sub admin has been Inactive successfully!');
    }

}
