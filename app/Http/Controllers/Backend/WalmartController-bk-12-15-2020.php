<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Walmart;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class WalmartController extends BackendController
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

        return backend_view('walmart.statistics_walmart_dashboard',compact('date'));
    }

    public function ajax_render_otd_charts(Request $request)
    {
        $date=$request->get('date');
       $query = "select store_name,store_name.store_num as store_num,
                    count(distinct sprint__tasks.sprint_id) as orders,
                    count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)
                    group by store_name.store_num order by store_name.store_num";
        $wmorders = DB::select($query);

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

        }
        //return view('backend.walmart.sub-views.ajax-render-view-otd-pichart-one');
        $odt_data_1 = ['y1'=>round((($totalcount-$walmartcounts[0]->wmlates)/$totalcount)*100,0),'y2'=> 100-round((($totalcount-$walmartcounts[0]->wmlates)/$totalcount)*100,0)];
        $odt_data_2 = ['y1'=>array_sum($performance) / count($performance),'y2'=>100-(array_sum($performance) / count($performance))];
        $odt_data_1 = ['y1'=>90,'y2'=>10];
        $odt_data_2 = ['y1'=>80,'y2'=>20];
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
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as wota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as mdel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as mlates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as mOTA,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as ydel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as ylates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '".date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date)))."' and '".$date." 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as yOTA
            from sprint__sprints 
            join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id) 
            join sprint__tasks_history on(sprint__tasks_history.sprint_id=sprint__tasks.sprint_id)
            where sprint__sprints.deleted_at IS NULL and type='pickup' and sprint__sprints.status_id=17 and sprint__sprints.active=0 and store_num IS NOT NULL
            and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)";

        $overcounts = DB::select($query3);

        $query = "select store_name,store_name.store_num as store_num,
            count(distinct sprint__tasks.sprint_id) as orders,
            count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
            from sprint__tasks
            join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
            join store_name on(store_name.store_num=sprint__sprints.store_num) 
            where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
            and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)
            group by store_name.store_num order by store_name.store_num";

        $wmorders = DB::select($query);


        $totalcount = 0;
        $totallates=0;
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
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)
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

        }
        // $data = [
        //     'categories'=> $wmstores,
        //     'data_set_one' =>$deleiveries,
        //     'data_set_two' => $lates,
        // ];
        $data = [
            'categories'=> ["Stockyard (1004)","Heartland (1061)","Scarborough NE (1080)","Woodbridge (1081)","Vaughan (1095)","Markham East (1109)","Richmond Hill (1116)","Dixie \/ Dundas (1126)","Erin Mills (1211)","Oakville (3064)","Richmond Hill (3195)","Leslieville (4002)","Cabbagetown (4006)","Queens Quay (4007)"],
            'data_set_one' => [32,1,18,13,21,21,28,54,10,7,20,17,20,2],
            'data_set_two' => [0,0,0,1,9,8,0,0,0,0,1,0,1,0],
        ];

        return response()->json(array('status' => true,'for'=>'walmart-orders','data'=>$data));
    }

    public function ajax_render_walmart_on_time_orders(Request $request)
    {
        $date=$request->get('date');
        $query = "select store_name
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)
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
                and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279) 
                and type='pickup' and store_num IS NOT NULL group by store_num order by store_num";

        $otadata = DB::select($query5);
        $otas = [];
        $otds = [];
        if(!empty($otadata)) {
            foreach($otadata as $data){

                if($data->arr!=0){
                    $otas[] = round(($data->ota/$data->arr)*100,0);
                }

                if($data->del!=0) {
                    $otds[] = round(($data->otd/$data->del)*100,0);
                }
            }
        }
        // $data = [
        //     'categories'=> $wmstores,
        //     'data_set_one' =>$otas,
        //     'data_set_two' => $otds,
        // ];*/
        $data = [
            'categories'=>  ["Stockyard (1004)","Heartland (1061)","Scarborough NE (1080)","Woodbridge (1081)","Vaughan (1095)","Markham East (1109)","Richmond Hill (1116)","Dixie \/ Dundas (1126)","Erin Mills (1211)","Oakville (3064)","Richmond Hill (3195)","Leslieville (4002)","Cabbagetown (4006)","Queens Quay (4007)"] ,
            'data_set_one' => [55,33,72,79,32,51,59,81,50,0,46,50,100],
            'data_set_two' => [100,100,100,90,34,55,100,100,100,100,91,100,50,100],
        ];

        return response()->json(array('status' => true,'for'=>'walmart-on-time-orders','data'=>$data));
    }
    public function ajax_render_total_orders_summary(Request $request)
    {
        $date = $request->date;
        $page = ($request->page < 1 )? 1 : $request->page;
        $Records_Per_page= 10;
        $offset_value = ($page-1) * $Records_Per_page;
        $total_count = 0;

        $query="SELECT DISTINCT
                a.store_name,
                b.merchant_order_num AS walmart_order_num,
                a.joey_name,
                b.sprint_id,
                b.status_id,
                CONVERT_TZ(
                a.schedule_pickup,
                'UTC',
                'America/Toronto'
                ) AS schedule_pickup,
                CONVERT_TZ(
                a.compliant_pickup,
                'UTC',
                'America/Toronto'
                ) AS compliant_pickup,
                a.arrival_time,
                a.departure_time,
                a.dropoff_eta,
                a.compliant_dropoff,
                a.pickup_eta,
                a.delivery_time,
                b.address 
                FROM
                (SELECT 
                CONCAT(first_name, ' ', last_name) AS joey_name,
                store_name,
                sprint__tasks.sprint_id,
                sprint__tasks.status_id,
                sprint__tasks.due_time,
                sprint__tasks.created_at,
                FROM_UNIXTIME(due_time + 1800) AS schedule_pickup,
                FROM_UNIXTIME(due_time + 900) AS compliant_pickup,
                (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')
                FROM
                    sprint__tasks_history 
                WHERE sprint__tasks_history.status_id = 67 and active = 0
                    AND sprint__tasks_id = sprint__tasks.id 
                LIMIT 1) AS arrival_time,
                (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')
                FROM
                    sprint__tasks_history 
                WHERE sprint__tasks_history.status_id = 15 
                    AND sprint_id = sprint__tasks.sprint_id 
                LIMIT 1) AS departure_time,
                FROM_UNIXTIME(due_time - 12600) AS compliant_dropoff,
                FROM_UNIXTIME(eta_time - 18000) AS pickup_eta,
                (SELECT 
                    FROM_UNIXTIME(eta_time - 18000) 
                FROM
                    sprint__tasks AS dtask 
                WHERE TYPE = 'dropoff' 
                    AND dtask.sprint_id = sprint__tasks.sprint_id 
                LIMIT 1) AS dropoff_eta,
                (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')
                FROM
                    sprint__tasks_history 
                WHERE sprint__tasks_history.status_id = 17 
                    AND sprint_id = sprint__tasks.sprint_id 
                LIMIT 1) AS delivery_time 
                FROM
                sprint__sprints 
                JOIN sprint__tasks 
                    ON (
                    sprint__tasks.sprint_id = sprint__sprints.id 
                    AND sprint__sprints.status_id != 36 
                    AND sprint__sprints.status_id != 35
                    ) 
                LEFT JOIN store_name 
                    ON (
                    sprint__sprints.store_num = store_name.store_num
                    ) 
                LEFT JOIN joeys 
                    ON (
                    joeys.id = sprint__sprints.joey_id
                    ) 
                WHERE sprint__tasks.type = 'pickup'
                AND sprint__tasks.deleted_at IS NULL 
                AND store_name.deleted_at IS NULL 
                AND sprint__tasks.status_id != 38 
                AND creator_id IN (476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)) AS a 
                JOIN 
                (SELECT 
                    sprint_id,
                    status_id,
                    address,
                    merchant_order_num 
                FROM
                    sprint__tasks 
                    JOIN locations 
                    ON (location_id = locations.id) 
                    LEFT JOIN merchantids ON (sprint__tasks.id = merchantids.task_id)
                WHERE sprint__tasks.type = 'dropoff' 
                    AND sprint__tasks.deleted_at IS NULL) AS b 
                ON (a.sprint_id = b.sprint_id) 
                WHERE b.status_id != 38 
                AND CONVERT_TZ(
                FROM_UNIXTIME(a.due_time),
                'UTC',
                'America/Toronto'
                ) LIKE '".$date."%' 
                ORDER BY b.sprint_id";

                // geting total count
                $total_count = count(DB::select($query));

                // now getting records with limit
                $query.=" LIMIT ".$Records_Per_page. " OFFSET ".$offset_value;
                $fullrecord = DB::select($query);

                $total_pages = ($total_count > $Records_Per_page ) ? $total_count / $Records_Per_page : 1;
                $html =  view('backend.walmart.sub-views.ajax-render-view-walmart-total-orders-summary',compact('fullrecord','date','page','total_pages'))
                    ->with('status',self::$status)
                    ->render();
                return response()->json(array('status' => true,'for'=>'total-orders-summary','html'=>$html));
    }
    public function ajax_render_walmart_stores_data(Request $request)
    {
        $date=$request->getdate;
        $query = "select store_name,store_name.store_num as store_num,
        count(distinct sprint__tasks.sprint_id) as orders,
        count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
        from sprint__tasks
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
        join store_name on(store_name.store_num=sprint__sprints.store_num) 
        where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' 
        and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279)
        group by store_name.store_num order by store_name.store_num";


        $query2= "select AVG(TIMESTAMPDIFF(SECOND,A.created_at,B.created_at)) as waits from
        (select distinct sprint__tasks.sprint_id,dep.created_at 
        from sprint__tasks_history as dep 
        join sprint__tasks on(sprint__tasks.sprint_id=dep.sprint_id) 
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id)
        where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and dep.status_id=67 and type='pickup' and sprint__tasks.status_id!=38 
        and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279) and sprint__sprints.status_id=17 order by sprint__sprints.id) as A join
        (select distinct sprint__tasks.sprint_id,dep.created_at,store_num 
        from sprint__tasks_history as dep 
        join sprint__tasks on(sprint__tasks.sprint_id=dep.sprint_id) 
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id)
        where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '".$date."%' and dep.status_id=15 and type='pickup' and sprint__tasks.status_id!=38 
        and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279) and sprint__sprints.status_id=17 order by sprint__sprints.id) as B 
        on(A.sprint_id=B.sprint_id)
        where store_num IS NOT NULL GROUP BY store_num order by store_num";

        $waittimes=DB::select($query2);

        $jocodes = DB::select("select id,code from order_code where deleted_at IS NULL and code_num=1");

        if(!empty($waittimes)){
            foreach ($waittimes as $time) {
                $wait[]= (int)$time->waits;
            }
        }


        $wmorders = DB::select($query);
        $html =  view('backend.walmart.sub-views.ajax-render-view-walmart-stores-data',compact('wmorders','date','waittimes'))->render();
        return response()->json(array('status' => true,'for'=>'walmart-stores-data','html'=>$html));
    }




}
