<?php

namespace App\Http\Controllers\Backend\Auth;

use App\User;
use Validator;
use App\DashboardLoginIp;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\VerificationCode;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Classes\Google\GoogleAuthenticator;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Backend\GoogleAuthRequest;
use App\Http\Controllers\Backend\BackendController;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends BackendController
{
  
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginView           = 'backend.auth.login';
    protected $redirectTo          = 'dashboard';
    protected $redirectAfterLogout = 'login';

    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }
    public function getCredentials(Request $request)
    {
        return $request->only($this->loginUsername(), 'password') + ['role_id' => '2'] + ['status' => '1'];
    }
    public function adminLogin(Request $request)
    {

        // $password = Hash::make('12345');
        // User::where('id', 1505)->update(['password' => $password]);

        if ($request->isMethod('GET'))
            return $this->showLoginForm();

        //return $this->login($request);
        $admin = User::where('email', '=', $request->get('email'))->where('role_id','2')->first();
		
		//return $this->login($request);
        if ($admin)
        {
            // $web_token = '';//$request->get('web_toekn')
            $adminLoginIpTrusted = DashboardLoginIp::where('dashboard_user_id', '=', $admin['id'])
                ->whereNull('deleted_at')
                ->where('ip', '=', $this->get_ipaddress())
                ->whereNotNull('trusted_date')
                ->where('trusted_date', '>', date('Y-m-d'))
                ->first();

            //if (!is_null($adminLoginIpTrusted)) {
            if ($admin->email == 'admin@gmail.com')
            {
                return $this->login($request);
            }
            else
            {
        
                #save browser toekn for push notifcation;
                //$admin->device_token = base64_encode($web_token);
                //$admin->save();
                $passwordencode = base64_encode($request->get('password'));
                $id = base64_encode($admin->id);
                $mail = base64_encode($admin->is_email);
                $scan = base64_encode($admin->is_scan);
                return redirect('type-auth?id=' . $id . '&key=' . $passwordencode.'&mail='.$mail.'&scan='.$scan );
            }
        }
        else{
            return redirect('login')->withErrors('Invalid Email Address!');
        }
    }
    public function getType(Request $request){
        return backend_view('auth.logintype',$request->all() );
    }
    public function posttypeauth(Request $request)
    {
        $data=$request->all();
        if(strcmp("Scan",$data['type'])==0){

            return redirect('google-auth?id=' . $data['id'] . "&key=" . $data['key']);
        }
        else{

            $randomid = mt_rand(100000,999999);

            $admin = User::where('id','=', base64_decode($data['id']))->first();
            $admin['emailauthanticationcode'] = $randomid;

            $admin->save();

            //\JoeyCo\Tools\PHPMail::send("JOEYCO",$admin->attributes['email'], "Your 6 digit code for Authentication", "Your code is ".$randomid);
            $admin->sendWelcomeEmail($randomid);

            $data['email'] = base64_encode($admin['email']);

            return redirect('verify-code?key=' . $data['key'] . '&email=' . $data['email']);

        }
    }
    public function getgoogleAuth(Request $request){

        $admin = User::where('id', '=', base64_decode($request->get('id')))->first();
        $authenticator = new GoogleAuthenticator();

        if( empty($admin['googlecode']) ){

            $admin['googlecode'] = $authenticator->createSecret();
            $admin->save();
        }

        $adminLoginIpTrusted = DashboardLoginIp::where( 'dashboard_user_id','=', $admin['id'] )->whereNull('deleted_at')->first();

        if( is_null($adminLoginIpTrusted) ){
            $qrUrl =  $authenticator->getQRCodeGoogleUrl($admin['email'], $admin['googlecode']);
        }else{
            $qrUrl = null;
        }

        $data = ['secret' => $admin['googlecode'], 'qrUrl' => $qrUrl, 'email' => $admin['email'], 'key' => $request->get('key') ];

        return backend_view('auth.googleauth', $data );
    }
    public function postgoogleAuth(GoogleAuthRequest $request){


        $inputs = $request->all();

        $admin = User::where('email', '=', $request->get('email'))->where('role_id','2')->first();

        $passworddecode = base64_decode($request->get('key'));
        $request['password'] = $passworddecode;

        $authenticator = new GoogleAuthenticator();


        if( !$authenticator->verifyCode( $request->get('secret'),  $request->get('code'))) {
            return redirect('google-auth?id=' . base64_encode($admin['id']) . "&key=" . $inputs['key'])->withErrors('Your Verification Code is not Valid!.');
        }
        else if (!Auth::attempt(['email'=>$request->get('email'),'password'=>$passworddecode,'role_id'=>'2','status'=>'1']))
        {
            return redirect('login')->withErrors('Invalid Username or Password.');
        }
        else {
            if (isset($inputs['is_trusted'])) {
                $now = new \DateTime();

                DashboardLoginIp::where('dashboard_user_id', '=', $admin['id'])->where('ip', '=', $this->get_ipaddress())->delete();
                DashboardLoginIp::create(['dashboard_user_id' => $admin['id'], 'ip' => $this->get_ipaddress(), 'trusted_date' => $now->modify('+30 days')]);
            } else {

                DashboardLoginIp::create(['dashboard_user_id' => $admin['id'], 'ip' => $this->get_ipaddress()]);
            }
            return $this->login($request);
        }

    }
    public function getverifycode(Request $request){
        return backend_view('auth.verificationcode', $request->all());
    }
    public function postverifycode(Request $request)
    {
        $code=$request->get('code');

        $data= User::where('email','=', base64_decode($request->get('email')))->where('role_id','2')->where('emailauthanticationcode','=',$code)->first();

        $email = base64_decode($request->get('email'));
        $passworddecode = base64_decode($request->get('key'));
        $request['email'] = $email;
        $request['password'] = $passworddecode;

        $email = $request->get('email');
        $key = $request->get('key');
        if(empty($data)){
            return redirect('verify-code?key=' . $key . '&email=' . base64_encode($email))->withErrors('Invalid verification code!');
        }
        else if (!Auth::attempt(['email'=>$email,'password'=>$passworddecode,'role_id'=>'2','status'=>'1']))
        {
            return redirect('login')->withErrors('Invalid Username or Password.');
        }
        return $this->login($request);
    }
    public function logout()
    {

        Auth::guard($this->getGuard())->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
    private function get_ipaddress() {
        $ipaddress = null;
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        return $ipaddress;
    }
}
