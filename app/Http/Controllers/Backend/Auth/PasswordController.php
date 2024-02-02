<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Backend\BackendController;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Http\Request;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends BackendController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $linkRequestView = 'backend.auth.reset_password';
    protected $resetView = 'backend.auth.reset_link';

    protected $redirectPath = 'dashboard'; // for reset password

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

       // config(['auth.passwords.users.email' => 'backend.emails.password']);
    }

    public function resetPasswordAction(Request $request, $token = null)
    {

        if ($request->isMethod('GET'))
            
            return $this->showResetForm($request, $token);

        return $this->postEmail($request);
    }


    /**
     * Show custom reset password form
     */
    public function send_reset_link_email(Request $request)
    {
        $user = User::where('email', $request->email)->where('role_id',$request->role_id)->first();
        if (!$user) {
            return redirect()
                ->route('password.request')->withErrors('Email not exist. try again!.');
        }
        $token_validate = \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('email', $request->email)
            ->where('role_id', $request->role_id)
            ->first();
        $token = hash('ripemd160',uniqid(rand(),true));
        if ($token_validate == null) {
            \Illuminate\Support\Facades\DB::table('password_resets')
                ->insert(['email'=> $request->email,'role_id' =>  $request->role_id,'token' => $token]);
        }
        else
        {
            \Illuminate\Support\Facades\DB::table('password_resets')->where('email', $request->email)
                ->where('role_id', $request->role_id)
                ->update(['token' => $token]);
        }

        $email = base64_encode ($request->email);
        $user->sendPasswordResetEmail($email,$user->full_name,$token,$request->role_id);
        return redirect()
            ->route('password.request')->with('status',  'We have e-mailed your password reset link!. Please also check Junk/Spam folder as well.!');
    }

    /**
     * Show custom reset password form
     */
    public function reset_password_from_show($email,$token,$role_id)
    {
        $email = base64_decode($email);

        if ($token == null || $email == null || $role_id == null) {
		
            return redirect()
                ->route('password.request');
        }
        $token_validate = \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('token', $token)
            ->where('email', $email)
            ->where('role_id', $role_id)
            ->first();
		
        if ($token_validate == null) {
            return redirect()
                ->route('password.request');
        }
        return view('backend.auth.custom-reset-password')->with(
            ['token' => $token_validate->token, 'email' => $token_validate->email, 'role_id' => $token_validate->role_id]
        );
    }

    /**
     * Update reset password
     */
    public function reset_password_update(Request $request)
    {

        $this->validate($request,$this->rules());
        $password_reset_data = \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->where('role_id', $request->role_id)
            ->first();
        if ($password_reset_data == null) {
            return redirect()
                ->route('password.request');
        }
        User::where('email', $request->email)->where('role_id', $request->role_id)->update([
            'password' => Hash::make($request->password),
        ]);
        \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->where('role_id', $request->role_id)
            ->update(['is_deleted' => 1]);

        return redirect()->route('login')
            ->with('status', 'Password reset successfully. Please enter your credentials and login');

    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }
}
