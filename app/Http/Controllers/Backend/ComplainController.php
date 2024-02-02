<?php

namespace App\Http\Controllers\Backend;

use App\Complain;
use App\Http\Requests\Backend\ComplainRequest;
use DateTime;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Backend\CategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadeRequest;
use App\Http\Controllers\Backend\BackendController;


class ComplainController extends BackendController
{

    /**
     * Get Complain
     * Muhammad Raqib
     * @date 11/10/2022
     */
    public function index(Request $request)
    {
        return backend_view('complain.register');
    }
    
    /**
     * Register Complain
     * Muhammad Raqib
     * @date 11/10/2022
     */

    public function create(ComplainRequest $request)
    {

        $complain_post = $request->all();

        $complain = new Complain();
        $complain->type = $complain_post['reason'];
        $complain->joey_id = Auth::user()->id;
        $complain->description = $complain_post['complain_data'];
        $complain->save();

        return back()->with('success','Complain Added Successfully!');

    }
    /*end*/
    /*List Complains*/
    
    public function get_complains(Request $request)
    {
        $date = $request->get('date');

        if(empty($date))
        {
            $date=date('Y-m-d');
        }

        $today_date = !empty($date) ? $date : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date));
        $start_dt = new DateTime($today_date . " 00:00:00");
        $start_dt->setTimeZone(new \DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');
        $end_dt = new DateTime($today_date . " 23:59:59");
        $end_dt->setTimeZone(new \DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $complain_data = Complain::with('dashboard_user')
                                ->where('created_at','>=',$start)
                                ->where('created_at','<=',$end)
                                ->WhereNull('deleted_at')
                                ->get();

        return backend_view('complain.index',compact('complain_data'));
    }

    /*end*/

}
