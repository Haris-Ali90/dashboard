<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class FrontendController  extends ApiBaseController {

    public function logout()
    {
        session_start();
        session_destroy();
        return redirect('login');
    }
    
    public function confirm_checkout(Request $request)
    {

    }
  
    
}