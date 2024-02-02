<?php

namespace App\Http\Controllers\Backend;

use App\FinanceVendorCity;
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

class SubadminController extends BackendController
{
    use ResetsPasswords;

    public function getIndex()
    {
   
        return backend_view('subadmin.index');
    }

    /**
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function subAdminList(Datatables $datatables, Request $request)
    {
        $query = User::where(['role_id' => User::ROLE_ADMIN])
                        ->where('email','!=','admin@gmail.com')
                        ->where('email','!=', Auth::user()->email);

        return $datatables->eloquent($query)
                ->setRowId(static function ($record) {
                    return $record->id;
                })
                ->editColumn('status', static function ($record) {
                    return backend_view('subadmin.status', compact('record') );
                })
                ->editColumn('profile_picture', static function ($record) {
                    if (isset($record->profile_picture)) {
                        return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->profile_picture . '" />';
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', static function ($record) {
                    return backend_view('subadmin.action', compact('record'));
                })
            ->make(true);
    }

    public function active(User $record)
    {
        $record->activate();
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin has been Active successfully!');
    }

    public function inactive(User $record)
    {
        $record->deactivate();
        return redirect()
            ->route('sub-admin.index')
            ->with('success', 'Sub Admin has been Inactive successfully!');
    }

    public function add(Roles $role)
    {

        $role = Roles::where('id','!=','1')
            ->where('type','=','joeyco_dashboard')->orderBy('display_name','ASC')
            ->get();
        
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();

        return backend_view( 'subadmin.add', compact('role','hubs') );
    }

    public function edit($id)
    {
        $sub_id = base64_decode($id);
        $user = User::find($sub_id);
        $role = Roles::where('id','!=','1')
            ->where('type','=','joeyco_dashboard')->orderBy('display_name','ASC')
            ->get();

        $userPermissoins = Auth::user()->getPermissions();
		
        $data = explode(',',$user->type);

        $permissions = explode(',',$user->permissions);
        $rights = explode(',',$user->rights);
        $statistics = explode(',',$user->statistics);
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        return backend_view( 'subadmin.edit', compact('role','user','permissions','userPermissoins','rights','statistics','hubs','data') );
    }


    public function create(StoreSubadminRequest $request,User $user)
    {
        $postData = $request->all();
         if ($request->get('rights')) {
            $rights = implode(',', $postData['rights']);
            $postData['rights'] = $rights;
        }

        if ($request->get('statistics')) {
            $statistics = implode(',', $postData['statistics']);
            $postData['statistics'] = $statistics;
        }

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

        $postData['role_id'] = User::ROLE_ADMIN;
        $postData['status'] = 1;


        $user->create( $postData );



        session()->flash('alert-success', 'Sub Admin has been created successfully!');

        //config(['auth.passwords.users.email' => 'backend.emails.password']);
        //$this->sendResetLinkEmail($request);
		$token = hash('ripemd160',uniqid(rand(),true));
        DB::table('password_resets')
            ->insert(['email'=> $postData['email'],'role_id' =>  User::ROLE_ADMIN,'token' => $token]);

        $email = base64_encode ($postData['email']);
        //$user->sendSubadminPasswordResetEmail($email,$postData['full_name'],$token,User::ROLE_ADMIN);

        return redirect( 'subadmins' . $user->id );

    }

    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'full_name'  => 'required|max:255',
            'email'      => 'required|email|max:255',
        ]);
        $postData = $request->all();

        $postData['type'] = ($request->has('type')) ? $postData['type'] : '';

        $updateRecord = [
            'full_name' => $postData['full_name'],
            'email' => $postData['email'],
            'phone' => $postData['phone'],
            'address' => $postData['address'],
            'type'=> $postData['type'],
            'role_type' => $postData['role_type'],

        ];
         if ($request->get('rights')) {
            $rights = implode(',', $postData['rights']);
            $updateRecord['rights'] = $rights;
        }
        if ($request->get('statistics')) {
            $statistics = implode(',', $postData['statistics']);
            $updateRecord['statistics'] = $statistics;
        }
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


        // dd($request);
        $user->update( $updateRecord );

        session()->flash('alert-success', 'Subadmin has been updated successfully!');
        return redirect( 'subadmins');
    }

    public function destroy(User $user)
    {
        // if ( $user->isAdmin() )
        //     abort(404);
        $userId = $user->id;
        $data = $user->delete();
       // Post::where('user_id',$userId)->delete();
        session()->flash('alert-success', 'Sub Admin has been deleted successfully!');

        return redirect( 'subadmins' );
    }

    public function profile($id)
    {
        $sub_id = base64_decode($id);
        $users        = User::where(['id' => $sub_id])->get();
        $users = $users[0];
        $rights = explode(',',$users->rights);
        return backend_view( 'subadmin.profile', compact('users','rights') );
    }

    public function getChangePwd()
    {
        return backend_view( 'changepwd');
    }

    public function changepwd(ChangepwdRequest $request)
    {

        $postData = $request->all();

        /*dd($password);*/
        $password=$postData['old_pwd'];
        $admin=User::where('email',auth()->user()->email)->where('role_id',2)->first();
        $hashpwd=$admin['password'];
        if (Hash::check($password, $hashpwd))
        {
//            dd($password=$postData['new_pwd']);
            if ( $request->has('new_pwd') && $request->get('new_pwd', '') != '' ) {
                $postData['new_pwd'] = \Hash::make( $postData['new_pwd'] );
                $newpwd=$postData['new_pwd'];
                User::where('email',auth()->user()->email)->where('role_id',2)->first()->update(['password' => $newpwd]);
                session()->flash('alert-success', 'Password has been change successfully!');
                return redirect( 'changepwd');
            }
        }
        else{

            session()->flash('alert-danger', 'Old password not Match!');
            return redirect( 'changepwd');

        }



    }

    public function adminedit($id){
        $sub_id = base64_decode($id);
        $user = User::find($sub_id);

//        dd($user);
        $permissions = explode(',',$user->permissions);
        $rights = explode(',',$user->rights);
        return backend_view( 'subadmin.adminedit', compact('user','permissions','rights') );

    }

    public function adminupdate(Request $request, User $user)
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
        $rights = implode(',', $postData['rights']);
        $updateRecord['rights'] = $rights;
        $permissions = implode(',', $postData['permissions']);
        $updateRecord['permissions'] = $permissions;
        if ( $request->has('password') && $request->get('password', '') != '' ) {
            $updateRecord['password'] = \Hash::make( $postData['password'] );
        }

        if($file = $request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture') ;

            $imageName = $user->id . '-' . \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $path = public_path().'/images/profile_images' ;

            $file->move($path,$imageName);
            $updateRecord['profile_picture'] = url('/').'/public/images/profile_images/'.$imageName ;
        }


        // dd($request);
        $user->update( $postData );

        session()->flash('alert-success', 'Admin has been updated successfully!');
        return redirect( 'adminedit/'.base64_encode(auth()->user()->id));
    }

    public function accountSecurityEdit($id)
    {
        $sub_id = base64_decode($id);
        $user = User::find($sub_id);
        return backend_view( 'subadmin.security', compact('user') );
    }

    public function accountSecurityUpdate(Request $request, User $user)
    {
        $this->validate($request,[
            'is_email' => 'required',
        ]);
        $postData = $request->all();
        $updateRecord = [
            'is_email' => isset($postData['is_email'])? 1: 0,
            'is_scan' => isset($postData['is_scan'])? 1: 0,
        ];

        $user->update( $updateRecord );

        session()->flash('alert-success', 'Account Security has been updated successfully!');
        return redirect( 'account/security/edit/'.base64_encode(auth()->user()->id));
    }
}
