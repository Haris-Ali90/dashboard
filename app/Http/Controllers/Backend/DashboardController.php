<?php

namespace App\Http\Controllers\Backend;

use App\AmazonEnteries;
use App\Post;
use Illuminate\Http\Request;
use App\Sprint;
use App\Http\Requests;
use App\Http\Controllers\Backend\BackendController;

use App\User;
use App\Teachers;
use App\Institute;
use App\Amazon;
use App\Amazon_count;
use App\Ctc;
use App\Ctc_count;
use App\CoursesRequest;
use date;
use DB;
use whereBetween;
use Carbon\Carbon;
use PDFlib;

class DashboardController extends BackendController
{
    /**
     * Get Montreal ,Ottawa ,  Ctc dashboard count and graph
     */
    public function getIndex(Request $request,$graph = null)
    {
        // dd(env('DB_DATABASE'));

        /*
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $amazon_montreal_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"),'like',$today_date."%")
            ->where(['vendor_id' => 477260])
            ->orderBy('id','DESC')
            ->first();

        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();

        $startdate = date('y') . '-01-01 00:00:00';
        $enddate = date('y') . '-12-31 23:59:59';


        $amazon_dashboard_count = Amazon_count::orderBy('created_at',"asc")->get()->toArray();
        $ctc_dashboard_count = Ctc_count::orderBy('created_at',"asc")->get()->toArray();

        $total_dashboard_count_data =  array_merge($amazon_dashboard_count,$ctc_dashboard_count);

        $months_array = [
            '1'=>['month'=>'Jan','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '2'=>['month'=>'Feb','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '3'=>['month'=>'Mar','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '4'=>['month'=>'Apr','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '5'=>['month'=>'May','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '6'=>['month'=>'Jun','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '7'=>['month'=>'Jul','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '8'=>['month'=>'Aug','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '9'=>['month'=>'Sep','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '10'=>['month'=>'Oct','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '11'=>['month'=>'Nov','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0],
            '12'=>['month'=>'Dec','montreal_total'=>0,'ottawa_total'=>0,'ctc_total'=>0]
        ];

        foreach($total_dashboard_count_data as $data)
        {

            $getting_month = (int) explode('-',$data['created_at'])[1];

            if(!isset($data['vendor_id'])) // checking for ctc
            {
                $months_array[$getting_month]['ctc_total']=  $months_array[$getting_month]['ctc_total'] + $data['total'];
            }
            elseif($data['vendor_id'] == '477260') // checking for montreal 477260
            {
                $months_array[$getting_month]['montreal_total']= $months_array[$getting_month]['montreal_total'] + $data['total'] ;
            }
            elseif($data['vendor_id'] == '477282') // checking for ottawa 477282
            {
                $months_array[$getting_month]['ottawa_total']= $months_array[$getting_month]['ottawa_total'] + $data['total'];
            }


        }
        $bar_chart_data = $months_array;
        $bar_chart_data =  json_encode($bar_chart_data,JSON_FORCE_OBJECT);


        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $montreal_count = Amazon_count::Where('created_at', 'like', $today_date . "%")
            ->where(['vendor_id' => 477260])
            ->first();

        $ottawa_count = Amazon_count::Where('created_at', 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->first();

        $ctc_count = Ctc_count::Where('created_at', 'like', $today_date . "%")
            ->first();

        $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
        $notscan_count = 0;//Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")->where(['creator_id' => 477260])->whereIn('status_id', [61,13])->count();

        $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
        $ottawa_notscan_count = 0;//Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")->where(['creator_id' => 477282])->whereIn('status_id', [61,13])->count();
        $type = 'all';
        return backend_view('dashboard', compact(
            'montreal_count',
            'ottawa_count',
            'ctc_count',
            'graph',
            'bar_chart_data',
            'amazon_count',
            'amazon_montreal_count',
            'notscan_count',
            'ottawa_notscan_count',
            'type'
        ));*/
     
        return backend_view('dashboard');

    }



}
