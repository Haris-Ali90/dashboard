<?php

namespace App\Http\Controllers\Backend;

use App\Task;
use DB;
use App\Walmart;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\OrderCode;
use Yajra\Datatables\Datatables;

class NewWalmartController extends BackendController
{
    public static $status = array("136" => "Client requested to cancel the order",
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

    public function getWalmart(Request $request)
    {
        $today_date = !empty($request->get('datepicker'))?$request->get('datepicker'):date("Y-m-d");

        return backend_view('walmart.walmart_dashboard');
    }

    public function walmartData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Walmart::where('created_at','like',$today_date."%");
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status_id', static function ($record) {
                return self::$status[$record->status_id];
            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart.action', compact('record'));
            })
            ->make(true);
    }

    public function walmartProfile(Request $request, $id)
    {
        $walmart_id = base64_decode($id);
        $walmart_dash = Walmart::where(['id' => $walmart_id])->get();
        $walmart_dash = $walmart_dash[0];

        return backend_view('walmart.walmart_profile', compact('walmart_dash'));
    }

    public function walmartExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $walmart_data = Walmart::where('created_at', 'like', $date . "%")->get();
        $walmart_array[] = array('Store Name', 'Walmart Order #', 'Joey Name', 'Address', 'Schedule Pickup', 'Drop Off Eta', 'Status');
        foreach ($walmart_data as $walmart) {
            $walmart_array[] = array(
                'Store Name' => $walmart->store_name,
                'Walmart Order #' => $walmart->walmart_order_num,
                'Joey Name' => $walmart->joey_name,
                'Address' => $walmart->address,
                'Schedule Pickup' => $walmart->schedule_pickup,
                'Drop Off Eta' => $walmart->dropoff_eta,
                'Status' =>   self::$status[$walmart->status_id]
            );
        }
        Excel::create("Walmart Data $date", function ($excel) use ($walmart_array) {
            $excel->setTitle('Walmart Data');
            $excel->sheet('Walmart Data', function ($sheet) use ($walmart_array) {
                $sheet->fromArray($walmart_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function statistics_wm(Request $request)
    {
        $date=($request->date != null) ? $request->date : date('Y-m-d');

        return backend_view('newwalmart.statistics_walmart_dashboard',compact('date'));
    }

    public function ajax_render_otd_charts(Request $request)
    {
        $date=$request->get('date');

        $wmorders=Task::join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
        ->join('store_name','store_name.store_num','=','sprint__sprints.store_num')
            ->leftJoin('sprint__tasks_history', function ($join) {
                $join->on('sprint__tasks_history.sprint_id','=','sprint__tasks.sprint_id')
                ->where('sprint__tasks_history.status_id','=',68);
            })
        ->whereIn('sprint__sprints.creator_id', [476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503])
            ->where(\DB::raw("CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto')"), 'like', $date . "%")
            ->where('sprint__tasks.type', 'pickup')->whereIn('sprint__sprints.status_id',[17,113,114])
            ->where('sprint__sprints.active',0)->where('store_name.deleted_at',null)
            ->groupBy('store_name.store_num')->orderBy('store_name.store_num','asc')
            ->get(['store_name.store_num',\DB::raw("COUNT('sprint__tasks.sprint_id') AS orders"),
                \DB::raw("count(case when from_unixtime(due_time+5700) < sprint__tasks_history.created_at then 1 else null end) as lates")]);


		

        $walmartcounts= OrderCode::join('order_assigned_code','code_id','=','order_code.id')
            ->Join('sprint__tasks', function ($join) {
                $join->on('sprint__tasks.sprint_id','=','order_assigned_code.sprint_id')
                    ->where('type','=','pickup');
            })
            ->Join('sprint__tasks_history', function ($join) {
                $join->on('sprint__tasks.sprint_id','=','sprint__tasks_history.sprint_id')
                    ->where('sprint__tasks_history.status_id','=',68);
            })
            ->Join('sprint__sprints', function ($join) {
                $join->on('sprint__sprints.id','=','sprint__tasks_history.sprint_id')
                    ->where('sprint__sprints.status_id','=',17);
            })->where('code_num',1)
            ->get([ \DB::raw("count(case when from_unixtime(due_time+5700) < sprint__tasks_history.created_at then order_assigned_code.sprint_id else null end) as wmlates")]);



        $totalcount = 0;

        if(!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $totalcount+= $wmorder->orders;

                $performance[] = 100-(($wmorder->lates*100)/$wmorder->orders);
            }

            $odt_data_1 = ['y1'=> round((($totalcount-$walmartcounts[0]->wmlates)/$totalcount)*100,0),'y2'=> 100-round((($totalcount-$walmartcounts[0]->wmlates)/$totalcount)*100,0)];
            $odt_data_2 = ['y1'=>array_sum($performance) / count($performance),'y2'=>100-(array_sum($performance) / count($performance))];

        }
        else
        {
            $odt_data_1 = ['y1'=>100,'y2'=>0];
            $odt_data_2 = ['y1'=>100,'y2'=>0];
        }

        return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$odt_data_1,$odt_data_2]));
    }

    public function ajax_render_short_summary(Request $request)
    {
        $date=$request->get('date');
        $walmartcounts= DB::select("select
            count(distinct(case when (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') like '".$date."%') and (from_unixtime(due_time+5700)<sprint__tasks_history.created_at) then order_assigned_code.sprint_id else null end)) as wmlates,
            count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') between '".date('y-m-d H:i:s', strtotime('-6 day', strtotime($date)))."' and '".$date."') then order_assigned_code.sprint_id else null end)) as wmwlates,
            count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') between '".date('y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date."') then order_assigned_code.sprint_id else null end)) as wmmlates,
            count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') between '2020-01-01' and '".$date."') then order_assigned_code.sprint_id else null end)) as wmylates
            from order_code join order_assigned_code on(code_id=order_code.id)
            join sprint__tasks on(sprint__tasks.sprint_id=order_assigned_code.sprint_id and type='pickup') 
            join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
            join sprint__sprints on(sprint__tasks_history.sprint_id=sprint__sprints.id and sprint__sprints.status_id=17)
            where code_num=1");
        $query3="select 
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') like '".$date."%') and (sprint__tasks_history.status_id=67) then 1 else null end) as arrivals,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') like '".$date."%') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as ota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('y-m-d H:i:s', strtotime('-6 day', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as wdel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as wlates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67)  then 1 else null end) as wa,            
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as wota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as mdel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as mlates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67)   then 1 else null end) as ma,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as mota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as ydel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as ylates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as ya,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as yota
            from sprint__sprints 
            join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id) 
            join sprint__tasks_history on(sprint__tasks_history.sprint_id=sprint__tasks.sprint_id)
            where sprint__sprints.deleted_at IS NULL and type='pickup' and sprint__sprints.status_id=17 and sprint__sprints.active=0 and store_num IS NOT NULL
            and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)";

        $overcounts = DB::select($query3);

        $query = "select store_name,store_name.store_num as store_num,
            count(distinct sprint__tasks.sprint_id) as orders,
            count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
            from sprint__tasks
            join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
            join store_name on(store_name.store_num=sprint__sprints.store_num) 
            where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
            and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
            group by store_name.store_num order by store_name.store_num";

        $wmorders = DB::select($query);


        $totalcount = 0;
        $totallates=0;
        $performance = [];
        if(!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $totalcount+= $wmorder->orders;
                $performance[] = 100-(($wmorder->lates*100)/$wmorder->orders);
            }

        }
        //return view('backend.walmart.sub-views.ajax-render-view-otd-pichart-one');
        $html =  view('backend.walmart.sub-views.ajax-render-view-short-summary',compact('totalcount','performance','wmorders','overcounts','date','walmartcounts'))->render();
        return response()->json(array('status' => true,'for'=>'short-summary','html'=>$html));
    }

    public function ajax_render_walmart_order(Request $request)
    {
        $date=$request->get('date');
        $query = "select store_name,store_name.store_num as store_num,
                    count(distinct sprint__tasks.sprint_id) as orders,
                    count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                    group by store_name.store_num order by store_name.store_num";
        $wmorders = DB::select($query);
        $totalcount = 0;
        $totallates=0;
        if(!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $totalcount+= $wmorder->orders;
                $wmstores[] = $wmorder->store_name;
                $deleiveries[] = $wmorder->orders;
                $lates[] =  $wmorder->lates;
                $totallates+=$wmorder->lates;
                $performance[] = 100-(($wmorder->lates*100)/$wmorder->orders);
            }

            $data = [
                'categories'=> $wmstores,
                'data_set_one' =>$deleiveries,
                'data_set_two' => $lates,
            ];

            return response()->json(array('status' => true,'for'=>'walmart-orders','data'=>$data));

        }
        else
        {
            return response()->json(array('status' => false,'for'=>'walmart-orders','data'=>[]));
        }

    }

    public function ajax_render_walmart_on_time_orders(Request $request)
    {
        $date=$request->get('date');
        $query = "select store_name
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                    group by store_name.store_num order by store_name.store_num";
        $wmorders = DB::select($query);
        $totalcount = 0;
        $totallates=0;
        if(!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $wmstores[] = $wmorder->store_name;
            }

        }
        $query5 = "select
                count(case when sprint__tasks_history.status_id=67  then 1 else null end) as arr,
                count(case when sprint__tasks_history.status_id=68  then 1 else null end) as del,
                count(case when (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as ota,
                count(case when (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as otd
                from sprint__sprints 
                join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id )
                join sprint__tasks_history on(sprint__tasks_history.sprint_id=sprint__tasks.sprint_id  and sprint__sprints.status_id!=36 and sprint__sprints.status_id!=35) 
                where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and sprint__sprints.deleted_at IS NULL and sprint__sprints.status_id=17 
                and sprint__sprints.active=0
                and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503) 
                and type='pickup' and store_num IS NOT NULL group by store_num order by store_num";

        $otadata = DB::select($query5);
        $otas = [];
        $otds = [];
        $data = [
            'categories'=> [],
            'data_set_one' =>[],
            'data_set_two' => [],
        ];
        if(!empty($otadata)) {
            foreach($otadata as $data){

                if($data->arr!=0){
                    $otas[] = round(($data->ota/$data->arr)*100,0);
                }

                if($data->del!=0) {
                    $otds[] = round(($data->otd/$data->del)*100,0);
                }
            }

            $data = [
                'categories'=> $wmstores,
                'data_set_one' =>$otas,
                'data_set_two' => $otds,
            ];
            return response()->json(array('status' => true,'for'=>'walmart-on-time-orders','data'=>$data));
        }
        else
        {

            return response()->json(array('status' => false,'for'=>'walmart-on-time-orders','data'=>$data));
        }

        /*$data = [
            'categories'=>  ["Stockyard (1004)","Heartland (1061)","Scarborough NE (1080)","Woodbridge (1081)","Vaughan (1095)","Markham East (1109)","Richmond Hill (1116)","Dixie \/ Dundas (1126)","Erin Mills (1211)","Oakville (3064)","Richmond Hill (3195)","Leslieville (4002)","Cabbagetown (4006)","Queens Quay (4007)"] ,
            'data_set_one' => [55,33,72,79,32,51,59,81,50,0,46,50,100],
            'data_set_two' => [100,100,100,90,34,55,100,100,100,100,91,100,50,100],
        ];*/


    }
    public function ajax_render_total_orders_summary(Request $request)
    {
        $date = $request->date;
        $page = ($request->page < 1 )? 1 : $request->page;
        $Records_Per_page= 10;
        $offset_value = ($page-1) * $Records_Per_page;
        $total_count = 0;

        $query="SELECT
                store_name,store_num,
                b.merchant_order_num AS walmart_order_num,
                joey_name,
                id AS sprint_id,
                status_id,
                ROUND(distance,3) AS distance,
                CONVERT_TZ(FROM_UNIXTIME(a.pickup_eta),'UTC','America/Toronto') AS pickup_eta,
                CONVERT_TZ(FROM_UNIXTIME(b.dropoff_eta),'UTC','America/Toronto') AS dropoff_eta,
                CONCAT(address,',',postal_code) AS address,
                CONVERT_TZ(schedule_pickup,'UTC','America/Toronto') AS schedule_pickup,
                CONVERT_TZ(compliant_pickup,'UTC','America/Toronto') AS compliant_pickup,
                CONVERT_TZ(compliant_dropoff,'UTC','America/Toronto') AS compliant_dropoff,
                CONVERT_TZ(FROM_UNIXTIME(MIN(joey_arrival_time)),'UTC','America/Toronto') AS arrival_time,
                CONVERT_TZ(FROM_UNIXTIME(MAX(joey_departure_time)),'UTC','America/Toronto') AS departure_time,
                CONVERT_TZ(FROM_UNIXTIME(MAX(atdrop_time)),'UTC','America/Toronto') AS atdrop_time,
                CONVERT_TZ(FROM_UNIXTIME(MAX(deliver_time)),'UTC','America/Toronto') AS delivery_time
                FROM
                (SELECT
                distance / 1000 AS distance,
                sprint__sprints.id AS id,
                store_name,store_name.store_num,
                sprint__sprints.status_id,
                CONCAT(first_name, ' ', last_name) AS joey_name,
                FROM_UNIXTIME(due_time + 1800) AS schedule_pickup,
                FROM_UNIXTIME(due_time + 900) AS compliant_pickup,
                FROM_UNIXTIME(due_time + 5400) AS compliant_dropoff,
                eta_time AS pickup_eta,
                CASE WHEN sprint__tasks_history.status_id = 67 AND sprint__tasks_history.active = 1 THEN UNIX_TIMESTAMP( sprint__tasks_history.created_at) ELSE NULL
                END AS joey_arrival_time,
                CASE WHEN sprint__tasks_history.status_id = 15 THEN UNIX_TIMESTAMP(sprint__tasks_history.resolve_time) ELSE NULL
                END AS joey_departure_time,
                CASE WHEN sprint__tasks_history.status_id = 68 THEN UNIX_TIMESTAMP( sprint__tasks_history.created_at ) ELSE NULL
                END AS atdrop_time,
                CASE WHEN sprint__tasks_history.status_id = 17 THEN UNIX_TIMESTAMP( sprint__tasks_history.resolve_time ) ELSE NULL
                END AS deliver_time
                FROM
                sprint__sprints
                JOIN sprint__tasks ON (sprint__tasks.sprint_id = sprint__sprints.id)
                JOIN store_name ON (store_name.store_num=sprint__sprints.store_num AND store_name.deleted_At IS NULL)
                LEFT JOIN sprint__tasks_history
                ON (
                sprint__tasks_history.sprint_id = sprint__sprints.id
                AND sprint__tasks_history.status_id IN (67,15,68,17) 
                AND sprint__tasks_history.date > DATE_SUB('".$date."', INTERVAL 2 DAY))
                LEFT JOIN joeys ON (sprint__sprints.joey_id = joeys.id)
                WHERE TYPE = 'pickup'
                AND sprint__sprints.creator_id IN (476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                AND sprint__sprints.status_id NOT IN (35, 36, 37, 38)
                AND sprint__sprints.deleted_at IS NULL
                AND CONVERT_TZ( FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') LIKE '".$date."%'
                AND sprint__tasks.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
                AND sprint__sprints.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
                ) AS A 
                JOIN
                (SELECT
                sprint_id,
                merchant_order_num ,locations.address,locations.postal_code,eta_time AS dropoff_eta
                FROM
                sprint__tasks
                JOIN merchantids ON (sprint__tasks.id = merchantids.task_id)
                JOIN locations ON (location_id = locations.id)
                WHERE sprint__tasks.type = 'dropoff'
                AND sprint__tasks.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
                AND merchantids.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
                ) b
                ON (a.id = b.sprint_id)
                GROUP BY a.id";


        $fullrecord = DB::select($query);

        $total_pages = ($total_count > $Records_Per_page ) ? $total_count / $Records_Per_page : 1;
        $html =  view('backend.walmart.sub-views.ajax-render-view-walmart-total-orders-summary',compact('fullrecord','date','page','total_pages'))
            ->with('status',self::$status)
            ->render();
        return response()->json(array('status' => true,'for'=>'total-orders-summary','html'=>$html));
    }
    public function ajax_render_walmart_stores_data(Request $request)
    {
        $date=$request->date;
        $query = "select store_name,store_name.store_num as store_num,
        count(distinct sprint__tasks.sprint_id) as orders,
        count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
        from sprint__tasks
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
        join store_name on(store_name.store_num=sprint__sprints.store_num) 
        where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
        and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
        group by store_name.store_num order by store_name.store_num";

        $query2= "select AVG(TIMESTAMPDIFF(SECOND,A.created_at,B.created_at)) as waits from
        (select distinct sprint__tasks.sprint_id,dep.created_at 
        from sprint__tasks_history as dep 
        join sprint__tasks on(sprint__tasks.sprint_id=dep.sprint_id) 
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id)
        where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and dep.status_id=67 and type='pickup' and sprint__tasks.status_id!=38 
        and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503) and sprint__sprints.status_id=17 order by sprint__sprints.id) as A join
        (select distinct sprint__tasks.sprint_id,dep.created_at,store_num 
        from sprint__tasks_history as dep 
        join sprint__tasks on(sprint__tasks.sprint_id=dep.sprint_id) 
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id)
        where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and dep.status_id=15 and type='pickup' and sprint__tasks.status_id!=38 
        and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503
        ) and sprint__sprints.status_id=17 order by sprint__sprints.id) as B 
        on(A.sprint_id=B.sprint_id)
        where store_num IS NOT NULL GROUP BY store_num order by store_num";

        $waittimes=DB::select($query2);
        $jocodes = DB::select("select id,code from order_code where deleted_at IS NULL and code_num=1");
        $wmorders = DB::select($query);
        $wmcodes = DB::select("select id,code from order_code where deleted_at IS NULL and code_num=0");

        $html =  view('backend.walmart.sub-views.ajax-render-view-walmart-stores-data',compact('wmorders','date','waittimes','jocodes','wmcodes'))->render();
        return response()->json(array('status' => true,'for'=>'walmart-stores-data','html'=>$html));
    }


    public function getWmExport(Request $request){

        $response = DB::select("SELECT
        id AS order_id,
        b.merchant_order_num AS walmart_order_num,
        DATE,
        address,
        store_num,
        joey_name,
        convert_Tz(schedule_pickup,'UTC','America/Toronto') AS schedule,
        convert_Tz(compliant_pickup,'UTC','America/Toronto') AS compliant_pick,
        convert_Tz(compliant_dropoff,'UTC','America/Toronto') AS compliant_drop,
        CONVERT_TZ(
        FROM_UNIXTIME(MIN(joey_arrival_time)),
        'UTC',
        'America/Toronto'
        ) AS arrival,
        CONVERT_TZ(
        FROM_UNIXTIME(MAX(joey_departure_time)),
        'UTC',
        'America/Toronto'
        ) AS departure,
        CONVERT_TZ(
        FROM_UNIXTIME(MAX(deliver_time)),
        'UTC',
        'America/Toronto'
        ) AS deliver,
        distance,
        note
        FROM
        (SELECT
        distance / 1000 AS distance,
        sprint__sprints.id AS id,
        CONVERT_TZ(
        sprint__sprints.created_at,
        'UTC',
        'America/Toronto'
        ) AS date,
        store_num,
        CONCAT(first_name, ' ', last_name) AS joey_name,
        FROM_UNIXTIME(due_time + 1800) AS schedule_pickup,
        FROM_UNIXTIME(due_time + 900) AS compliant_pickup,
        FROM_UNIXTIME(due_time + 5400) AS compliant_dropoff,
        CASE
        WHEN sprint__tasks_history.status_id = 67 AND sprint__tasks_history.active = 1
        THEN UNIX_TIMESTAMP(
        sprint__tasks_history.created_at
        )
        ELSE NULL
        END AS joey_arrival_time,
        CASE
        WHEN sprint__tasks_history.status_id = 15
        THEN UNIX_TIMESTAMP(
        sprint__tasks_history.resolve_time
        )
        ELSE NULL
        END AS joey_departure_time,
        CASE
        WHEN sprint__tasks_history.status_id = 17
        THEN UNIX_TIMESTAMP(
        sprint__tasks_history.resolve_time
        )
        ELSE NULL
        END AS deliver_time,
        note
        FROM
        sprint__sprints
        JOIN sprint__tasks
        ON (
        sprint__tasks.sprint_id = sprint__sprints.id
        )
        LEFT JOIN sprint__tasks_history
        ON (
        sprint__tasks_history.sprint_id = sprint__sprints.id
        AND sprint__tasks_history.status_id IN (67, 15,17)
        AND sprint__tasks_history.date > DATE_SUB('" . $request->date . "', INTERVAL 2 DAY)
        )
        LEFT JOIN joeys
        ON (
        sprint__sprints.joey_id = joeys.id
        )
        LEFT JOIN notes
        ON (object_id = sprint__sprints.id)
        WHERE TYPE = 'pickup'
        AND sprint__sprints.creator_id IN (476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
        AND sprint__sprints.status_id NOT IN (35, 36, 37, 38)
        AND sprint__sprints.deleted_at IS NULL
        AND CONVERT_TZ(
        FROM_UNIXTIME(sprint__tasks.due_time),
        'UTC',
        'America/Toronto'
        ) LIKE '" . $request->date . "%'
        AND sprint__tasks.created_at > DATE_SUB('" . $request->date . "', INTERVAL 2 DAY)
        AND sprint__sprints.created_at > DATE_SUB('" . $request->date . "', INTERVAL 2 DAY)
        ) AS A JOIN
        (SELECT
        sprint_id,
        merchant_order_num , locations.address
        FROM
        sprint__tasks
        LEFT JOIN merchantids ON (sprint__tasks.id = merchantids.task_id)
        JOIN locations ON (location_id = locations.id)
        WHERE sprint__tasks.type = 'dropoff'
        AND sprint__tasks.created_at > DATE_SUB('" . $request->date . "', INTERVAL 2 DAY)
        AND merchantids.created_at > DATE_SUB('" . $request->date . "', INTERVAL 2 DAY)) b
        ON (a.id = b.sprint_id)
        GROUP BY a.id");

        //header info for browser
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=wmreport.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "Order Id\tWalmart Order Number\tDate\tAddress\tStore Name\tDriver Name\tSchedule Pickup\tCompliant Pickup\tCompliant Dropoff\tDriver Arrival\tWaiting Time\tPick-up Compliant\tDriver Departure\tDelivery to Customer\tWindow Expiration\tCompliant Walmart\tOrder Pick-up - Drop Off Duration\tDistance\tReason\tCodes\t\n";

        foreach($response as $record) {

            echo "CR-".$record->order_id."\t";

            if(!empty($record->walmart_order_num)) echo trim(preg_replace('/\s+/', ' ',$record->walmart_order_num));
            echo "\t";

            echo $record->date."\t";

            if(!empty($record->address)) echo stripcslashes($record->address)."\t";
            if(!empty($record->store_num)) echo $record->store_num;
            echo "\t";

            echo $record->joey_name."\t";
            echo $record->schedule."\t";
            echo $record->compliant_pick."\t";
            echo $record->compliant_drop."\t";
            if (strtotime($record->arrival) > strtotime('2000-01-01 00:00:00')) {
                echo $record->arrival."\t";
            } else {
                echo "\t";
            }

            if(!empty($record->departure) && !empty($record->arrival)){
                if (strtotime($record->arrival) > strtotime('2000-01-01 00:00:00')) {
                    $date1=date_create($record->arrival);
                    $date2=date_create($record->departure);
                    $diff=date_diff($date1,$date2);
                    echo $diff->format("%h:%i:%s");
                }
            }
            echo "\t";

            $gracecompliant = "20".date("y-m-d H:i:s",strtotime($record->compliant_pick." +5 minutes"));
            if($gracecompliant >= $record->arrival) echo "True\t"; else echo "False\t";

            if (strtotime($record->departure) > strtotime('2000-01-01 00:00:00')) {
                echo $record->departure."\t";
            } else {
                echo "\t";
            }

            $delivery = $record->deliver;

            echo $record->deliver."\t";
            // if (strtotime($record->deliver) > strtotime('2000-01-01 00:00:00')) {
            // echo $delivery."\t";
            // }
            // else {
            // echo "\t";
            // }

            $windowexp = "20".date("y-m-d H:i:s",strtotime($record->compliant_drop)+300);
            echo $windowexp."\t";

            if($windowexp >= $delivery) echo "True\t"; else echo "False\t";

            if(!empty($record->departure)) {
                if (strtotime($record->deliver) > strtotime('2020-01-01 00:00:00')) {
                    $date1=date_create($record->departure);
                    $date2=date_create($delivery);
                    $dur = date_diff($date2,$date1);
                    echo $dur->format("%i:%s");
                }
            }
            echo "\t";

            if(!empty($record->distance)) echo $record->distance."km\t";
            if(!empty($record->note)) echo $record->note; echo "\t";

            $codes = OrderCode::join('order_assigned_code','code_id','=','order_code.id')
                ->where('sprint_id','=',$record->order_id)->get();
            if(!empty($codes)) {
                foreach($codes as $code) {
                    echo $code->code.",";
                }
                echo "\t";
            }
            echo "\n";
        }

    }


}
