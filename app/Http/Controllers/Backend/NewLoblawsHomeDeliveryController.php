<?php

namespace App\Http\Controllers\Backend;

use App\Classes\RestAPI;
use App\Http\Requests\Backend\LoblawsHomeDeliveryRequest;
use App\Sprint;
use App\Task;
use App\Walmart;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class NewLoblawsHomeDeliveryController extends BackendController
{
    public static $status_code = array("136" => "Client requested to cancel the order",
        "145" => 'Returned To Merchant',
        "137" => "Delay in delivery due to weather or natural disaster",
        "118" => "left at back door",
        "117" => "left with concierge",
        "135" => "Customer refused delivery",
        "108" => "Customer unavailable-Incorrect address",
        "106" => "Customer unavailable - delivery returned",
        "107" => "Customer unavailable - Left voice mail - order returned",
        "109" => "Customer unavailable - Incorrect phone number",
        "142" => "Damaged at hub (before going OFD)",
        "143" => "Damaged on road - undeliverable",
        "144" => "Delivery to mailroom",
        "103" => "Delay at pickup",
        "139" => "Delivery left on front porch",
        "138" => "Delivery left in the garage",
        "114" => "Successful delivery at door",
        "113" => "Successfully hand delivered",
        "120" => "Delivery at Hub",
        "110" => "Delivery to hub for re-delivery",
        "111" => "Delivery to hub for return to merchant",
        "121" => "Pickup from Hub",
        "102" => "Joey Incident",
        "104" => "Damaged on road - delivery will be attempted",
        "105" => "Item damaged - returned to merchant",
        "129" => "Joey at hub",
        "128" => "Package on the way to hub",
        "140" => "Delivery missorted, may cause delay",
        "116" => "Successful delivery to neighbour",
        "132" => "Office closed - safe dropped",
        "101" => "Joey on the way to pickup",
        "32"  => "Order accepted by Joey",
        "14"  => "Merchant accepted",
        "36"  => "Cancelled by JoeyCo",
        "124" => "At hub - processing",
        "38"  => "Draft",
        "18"  => "Delivery failed",
        "56"  => "Partially delivered",
        "17"  => "Delivery success",
        "68"  => "Joey is at dropoff location",
        "67"  => "Joey is at pickup location",
        "13"  => "At hub - processing",
        "16"  => "Joey failed to pickup order",
        "57"  => "Not all orders were picked up",
        "15"  => "Order is with Joey",
        "112" => "To be re-attempted",
        "131" => "Office closed - returned to hub",
        "125" => "Pickup at store - confirmed",
        "61"  => "Scheduled order",
        "37"  => "Customer cancelled the order",
        "34"  => "Customer is editting the order",
        "35"  => "Merchant cancelled the order",
        "42"  => "Merchant completed the order",
        "54"  => "Merchant declined the order",
        "33"  => "Merchant is editting the order",
        "29"  => "Merchant is unavailable",
        "24"  => "Looking for a Joey",
        "23"  => "Waiting for merchant(s) to accept",
        "28"  => "Order is with Joey",
        "133" => "Packages sorted",
        "55"  => "ONLINE PAYMENT EXPIRED",
        "12"  => "ONLINE PAYMENT FAILED",
        "53"  => "Waiting for customer to pay",
        "141" => "Lost package",
        "60"  => "Task failure",
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow');


    /**
     * Get Loblaws Home Delivery
     */
    public function statistics_loblaws_index(Request $request)
    {
        $date=$request->get('date');
       if(empty($date))
       {
           $date=date("Y-m-d");
       }
        return backend_view('newloblawshomedelivery.statistics_loblawshomedelivery_dashboard',compact('date'));
    }

    /**
     * Get Loblaws Home Delivery OTD graph
     */
    public function ajax_render_otd_charts(Request $request)
    {
        $date=$request->get('date');

        $fullrecord=Task::join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->whereIn('sprint__sprints.creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])->where(\DB::raw("CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.eta_time),'UTC','America/Toronto')"), 'like', $date . "%")
            ->where('sprint__tasks.deleted_at', null)->where('sprint__sprints.deleted_at', null)
            ->WhereNotIn('sprint__sprints.status_id', [35,36,37,38])
            ->where('sprint__tasks.type', 'dropoff')->orderBy('sprint__sprints.id', 'asc')
            ->orderBy('sprint__tasks.ordinal', 'asc')
            ->get([\DB::raw("concat(sprint__sprints.id,'-',sprint__tasks.ordinal-1) as order_id"),'sprint__tasks.id','sprint__tasks.contact_id','sprint__sprints.id as sprint_id','sprint__sprints.status_id','sprint__tasks.status_id as task_status_id' ,\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta"),'sprint__tasks.location_id','sprint__tasks.ordinal']);

        $total=0;
        $otd=0;
        if(!empty($fullrecord)) {

            foreach ($fullrecord as $record) {
                if ($record->loblawsHomeTaskMerchants) {
                    if ($record->loblawsHomeTaskMerchants->end_time != NULL) {
                        if ($record->lablaws_home_delivery_time) {
                            if(date('Y-m-d',strtotime($record->dropoff_eta)).' '.date('H:i:s',strtotime($record->loblawsTaskMerchants->end_time)+300) > date('Y-m-d H:i:s',strtotime($record->lablaws_home_delivery_time->delivery_time))){
                                $otd++;
                            }
                        } else {
                            $otd++;
                        }
                    } else {
                        $otd++;
                    }
                }
                $total++;
            }
        }

         $data_set_one = ['y1'=>100,'y2'=>0,"tag1"=>"No Deliveries","tag2"=>"No Deliveries"];
         if($otd==0)
         {
             return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$data_set_one]));
         }
         $data_set_one =  ['y1'=>round((($otd)/$total)*100,0),'y2'=>100-round((($otd)/$total)*100,0),"tag1"=>"On Time Deliveries","tag2"=>"Off Time Deliveries"]; 
    
        return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$data_set_one]));
    }

    /**
     * Get Loblaws Home Delivery Orders
     */
    public function ajax_render_loblaws_orders(Request $request)
    {
        $fullrecord=[];
        $date= ($request->date != null) ? $request->date : date("Y-m-d");
        $page= (empty($request->page)) ? 1 : $request->page;
        $fullrecord=Task::join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->whereIn('sprint__sprints.creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])->where(\DB::raw("CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.eta_time),'UTC','America/Toronto')"), 'like', $date . "%")
            ->where('sprint__tasks.deleted_at', null)->where('sprint__sprints.deleted_at', null)
            ->WhereNotIn('sprint__sprints.status_id', [38])
            ->where('sprint__tasks.type', 'dropoff')->orderBy('sprint__sprints.id', 'asc')
            ->orderBy('sprint__tasks.ordinal', 'asc')
            ->get([\DB::raw("concat(sprint__sprints.id,'-',sprint__tasks.ordinal-1) as order_id"),'sprint__tasks.id','sprint__tasks.contact_id','sprint__sprints.id as sprint_id','sprint__sprints.status_id','sprint__tasks.status_id as task_status_id' ,\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta"),'sprint__tasks.location_id','sprint__tasks.ordinal','sprint__sprints.distance']);

        $total=0;
        $otd=0;
        if(!empty($fullrecord)) {

            foreach ($fullrecord as $record) {
                if ($record->loblawsHomeTaskMerchants) {
                    if ($record->loblawsHomeTaskMerchants->end_time != NULL) {
                        if ($record->lablaws_home_delivery_time) {
                            if(date('Y-m-d',strtotime($record->dropoff_eta)).' '.date('H:i:s',strtotime($record->loblawsTaskMerchants->end_time)+300) > date('Y-m-d H:i:s',strtotime($record->lablaws_home_delivery_time->delivery_time))){
                                $otd++;
                            }
                        } else {
                            $otd++;
                        }
                    } else {
                        $otd++;
                    }
                }
                $total++;
            }
        }
              $total_page=1;

        $html =  view('backend.newloblawshomedelivery.sub-views.ajax-render-view-loblaws-orders',compact('fullrecord','page','total_page','total','otd'))->render();
        return response()->json(array('status' => true,'for'=>'loblawshomedelivery-orders','html'=>$html));
    }

    /**
     * Get Loblaws Home Delivery OTA graph
     */
    public function ajax_render_ota_charts(Request $request)
    {
        $date=$request->get('date');
        $fullrecord=Task::join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->whereIn('sprint__sprints.creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])->where(\DB::raw("CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.eta_time),'UTC','America/Toronto')"), 'like', $date . "%")
            ->where('sprint__tasks.deleted_at', null)->where('sprint__sprints.deleted_at', null)
            ->WhereNotIn('sprint__sprints.status_id', [35,36,37,38])
            ->where('sprint__tasks.type', 'dropoff')->orderBy('sprint__sprints.id', 'asc')
            ->orderBy('sprint__tasks.ordinal', 'asc')
            ->get(['sprint__tasks.id',\DB::raw("CONVERT_TZ(FROM_UNIXTIME(due_time),'UTC','America/Toronto') AS due_time"),\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Edmonton') AS dropoff_eta")]);

        $total=0;
        $otdss =0;
        $otass = 0;
		$otds = [];
        if(!empty($fullrecord)) {

            foreach ($fullrecord as $record) {
                 if ($record->loblawsHomeTaskMerchants) {
                    if ($record->loblawsHomeTaskMerchants->end_time != NULL) {
                        if ($record->lablaws_home_delivery_time) {
                            if(date('Y-m-d',strtotime($record->dropoff_eta)).' '.date('H:i:s',strtotime($record->loblawsTaskMerchants->end_time)+300) > date('Y-m-d H:i:s',strtotime($record->lablaws_home_delivery_time->delivery_time))){
                                $otdss++;
                            }
                        } else {
                            $otdss++;
                        }
                    } else {
                        $otdss++;
                    }
                }
                $total++;
            }
        }
   
		 if($total!=0) {
                $otds[] = round((($otdss)/$total)*100);
        }
        $fullrecord=Sprint::join('sprint__tasks','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('sprint__tasks_history','sprint__tasks_history.sprint_id','=','sprint__tasks.sprint_id')
            ->whereIn('sprint__sprints.creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])->where(\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"), 'like', $date . "%")
            ->where('sprint__sprints.active', 0)->where('sprint__sprints.deleted_at', null)
            ->WhereNotIn('sprint__sprints.status_id', [35,36,37,38])
            ->where('sprint__tasks.type', 'pickup')
            ->get([ \DB::raw("COUNT(CASE WHEN sprint__tasks_history.status_id=67 THEN 1 ELSE NULL END) as arr"),
                \DB::raw("COUNT(CASE WHEN (sprint__tasks_history.status_id=67) and (from_unixtime(due_time)>sprint__tasks_history.created_at) THEN 1 ELSE NULL END) as ota")]);
        $otas = [];
        if(!empty($fullrecord)) {
            foreach($fullrecord as $data){

                if($data->arr!=0){
                    $otas[] = round(($data->ota/$data->arr)*100,0);
                }

            }
        }
       
        return response()->json(array('status' => true,'for'=>'loblawshomedelivery-ota',
		'data_set_one' =>
            //[60],
                $otas,
            'data_set_two' =>
            //[40]
                $otds
        ));

    }

    /**
     * Get Loblaws Home Delivery new order count
     */
    public function loblawsNewCount()
    {
        $current_date = date("Y-m-d H:i:s");
        $pervious_date = date('Y-m-d H:i:s',strtotime('-20 seconds',strtotime(date("Y-m-d H:i:s"))));
        $pervious_count = \Illuminate\Support\Facades\DB::table('sprint__sprints')->whereIn('creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])->WhereNotIn('sprint__sprints.status_id', [35,36,37,38])->where('created_at', '<=', $pervious_date)->count();
        $new_count = \Illuminate\Support\Facades\DB::table('sprint__sprints')->whereIn('creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])->WhereNotIn('sprint__sprints.status_id', [35,36,37,38])->where('created_at', '<=', $current_date)->count();
        return $new_count - $pervious_count;

    }

    /**
     * Loblaws Home Delivery Dashboard Order Reporting Csv View
     */
    public function loblaws_homedelivery_dashboard_csv_index()
    {
        return backend_view('newloblawshomedelivery.loblaws_homedelivery_orders_report_view');
    }

    /**
     * Loblaws Home Delivery Dashboard Order Reporting Ajax Download
     */
    public function loblaws_homedelivery_dashboard_csv_download(LoblawsHomeDeliveryRequest $request)
    {
        // getting date from request
        $from_date = !empty($request->fromdatepicker) ? $request->fromdatepicker." 00:00:00" : date("Y-m-d 00:00:00");
        $to_date = !empty($request->todatepicker) ? $request->todatepicker." 23:59:59"  : date("Y-m-d 23:59:59");

        // getting limit
        $limit = ($request->limit) ? (int) $request->limit : 15;

        // creating metaData
        $metaData = $request->all();



        // creating file name if not exist in request
        $file_name = (isset($metaData['file_name'])) ? $metaData['file_name'] :'Loblaws Home Delivery Dashboard Order Report File '.date("M d, Y",strtotime($request->fromdatepicker)).'-('.uniqid().').csv';

        // update metaData with file name
        $metaData['file_name'] = $file_name;

        // creating file path
        $path = public_path().'/dashboard-reports/'.$file_name;

        //creating download path
        $metaData['downloadPath'] = url('/dashboard-reports/'.$file_name);

        // creating csv header
        $csv_header = ['Route #','Joey','Merchant Order #','Store Name','Customer Address','Planned Arrival','Arrival time @ PU','Actual Departure','Time Open','Time Close','Estimated Delivery ETA','Actual Arrival @ CX','Wait Time','Drive Time','Distance','Status','JoeyCo Notes / Comments'];

        // open or create file
        $file = fopen($path, 'a');

        // add header file on new file
        if($request->file_name == null)
        {
            fputcsv($file, $csv_header);
        }

        //dd($file);

        $fullrecord=Task::join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->whereIn('sprint__sprints.creator_id', [477205,477464,477465,477471,477472,477473,477474,477475])
            ->whereBetween(\DB::raw("CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.eta_time),'UTC','America/Toronto')"),[$from_date ,$to_date])
            ->where('sprint__tasks.deleted_at', null)
            ->where('sprint__sprints.deleted_at', null)
            ->WhereNotIn('sprint__sprints.status_id', [35,36,37,38])
            ->where('sprint__tasks.type', 'dropoff')
            ->orderBy('sprint__sprints.id', 'asc')
            ->orderBy('sprint__tasks.ordinal', 'asc')
            ->select([\DB::raw("concat(sprint__sprints.id,'-',sprint__tasks.ordinal-1) as order_id"),'sprint__tasks.id','sprint__tasks.contact_id','sprint__sprints.id as sprint_id','sprint__sprints.status_id','sprint__tasks.status_id as task_status_id' ,\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta"),'sprint__tasks.location_id','sprint__tasks.ordinal','sprint__sprints.distance'])
            ->paginate($limit)
            /*get([\DB::raw("concat(sprint__sprints.id,'-',sprint__tasks.ordinal-1) as order_id"),'sprint__tasks.id','sprint__tasks.contact_id','sprint__sprints.id as sprint_id','sprint__sprints.status_id','sprint__tasks.status_id as task_status_id' ,\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta"),'sprint__tasks.location_id','sprint__tasks.ordinal'])*/;
        //dd($fullrecord);
        foreach($fullrecord as $data)
        {

            $joey = $data->sprint->loblawsJoey?$data->sprint->loblawsJoey->joey_name:'';
            $merchant_no = $data->loblawsHomeTaskMerchants?$data->loblawsHomeTaskMerchants->merchant_order_num:'';
            $store_no = $data->sprint->loblawsContactTasks?$data->sprint->loblawsContactTasks->loblawsContacts->name:'';
            $customer_address = $data->task_Location->address;
            $planned_arrival = $data->sprint->loblawsHomeTasks?$data->sprint->loblawsHomeTasks->arrival_eta:'';
            $arrival_time_PU = $data->sprint->loblaws_home_arrival_time?$data->sprint->loblaws_home_arrival_time->arrival_time:'';
            $actual_departure = $data->sprint->loblaws_home_departure_time?$data->sprint->loblaws_home_departure_time->departure_time:'';
            $time_open = $data->loblawsHomeTaskMerchants? date('H:i',strtotime($data->loblawsHomeTaskMerchants->start_time)):'';
            $time_close = $data->loblawsHomeTaskMerchants? date('H:i',strtotime($data->loblawsHomeTaskMerchants->end_time)):'';
            $estimate_delivery_time =  $data->dropoff_eta;
            $actual_arrival_CX = $data->lablaws_home_delivery_time?$data->lablaws_home_delivery_time->delivery_time:'';

            $wait_time="00:00:00";
            if($data->sprint->loblaws_home_departure_time!=NULL && $data->sprint->loblaws_home_arrival_time!=NULL)
            {
                $date1=date_create($data->sprint->loblaws_home_arrival_time->arrival_time);
                $date2=date_create($data->sprint->loblaws_home_departure_time->departure_time);
                $diff=date_diff($date1,$date2);
                $wait_time=$diff->format("%H:%i:%S");
            }

            $drive_time="00:00:00";
            if($data->lablaws_home_delivery_time!=NULL && $data->sprint->loblaws_home_departure_time!=NULL)
            {
                $date1=date_create($data->sprint->loblaws_home_departure_time->departure_time);
                $date2=date_create($data->lablaws_home_delivery_time->delivery_time);
                $diff=date_diff($date1,$date2);
                $drive_time=$diff->format("%H:%i:%S");
            }


            //$signature = ($data->LablawsSprintConfirmationsSignature)?$data->LablawsSprintConfirmationsSignature->attachment_path:'';
            //dd($signature);
            $distance = round($data->distance/1000,3).'km';
            $statuss = $data->status_id;

            $status = self::$status_code[$statuss];

            $notes = $data->sprint->SprintNotes->implode('note',' .');

            $csv_row=[
                $route_no = $data->sprint_id,
                $joey,
                $merchant_no,
                $store_no,
                $customer_address,
                $planned_arrival,
                $arrival_time_PU,
                $actual_departure,
                $time_open,
                $time_close,
                $estimate_delivery_time,
                $actual_arrival_CX,
                $wait_time,
                $drive_time,
                //$signature,
                $distance,
                $status,
                $notes

            ];


            fputcsv($file,$csv_row);
        }


        return RestAPI::setPagination($fullrecord)->response([],200,'',$metaData);
    }

}
