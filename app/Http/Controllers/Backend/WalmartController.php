<?php

namespace App\Http\Controllers\Backend;

use App\BoradlessDashboard;
use App\CTCEntry;
use App\CtcVendor;
use App\JoeyRouteLocations;
use App\MerchantIds;
use App\Notes;
use App\SprintReattempt;
use App\SprintTaskHistory;
use App\TaskHistory;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use DB;
use App\Walmart;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\OrderCode;
use Yajra\Datatables\Datatables;

use App\WalmartStoreVendors;
use App\Task;
use App\Sprint;
use App\Classes\RestAPI;
use App\Http\Requests\Backend\WalmartRequest;

class WalmartController extends BackendController
{
    public static $status = array("136" => "Client requested to cancel the order", "137" => "Delay in delivery due to weather or natural disaster", "118" => "left at back door", "117" => "left with concierge", "135" => "Customer refused delivery", "108" => "Customer unavailable-Incorrect address", "106" => "Customer unavailable - delivery returned", "107" => "Customer unavailable - Left voice mail - order returned", "109" => "Customer unavailable - Incorrect phone number", "142" => "Damaged at hub (before going OFD)", "143" => "Damaged on road - undeliverable", "144" => "Delivery to mailroom", "103" => "Delay at pickup", "139" => "Delivery left on front porch", "138" => "Delivery left in the garage", "114" => "Successful delivery at door", "113" => "Successfully hand delivered", "120" => "Delivery at Hub", "110" => "Delivery to hub for re-delivery", "111" => "Delivery to hub for return to merchant", "121" => "Pickup from Hub", "102" => "Joey Incident", "104" => "Damaged on road - delivery will be attempted", "105" => "Item damaged - returned to merchant", "129" => "Joey at hub", "128" => "Package on the way to hub", "140" => "Delivery missorted, may cause delay", "116" => "Successful delivery to neighbour", "132" => "Office closed - safe dropped", "101" => "Joey on the way to pickup", "32" => "Order accepted by Joey", "14" => "Merchant accepted", "36" => "Cancelled by JoeyCo", "124" => "At hub - processing", "38" => "Draft", "18" => "Delivery failed", "56" => "Partially delivered", "17" => "Delivery success", "68" => "Joey is at dropoff location", "67" => "Joey is at pickup location", "13" => "At hub - processing", "16" => "Joey failed to pickup order", "57" => "Not all orders were picked up", "15" => "Order is with Joey", "112" => "To be re-attempted", "131" => "Office closed - returned to hub", "125" => "Pickup at store - confirmed", "61" => "Scheduled order", "37" => "Customer cancelled the order", "34" => "Customer is editting the order", "35" => "Merchant cancelled the order", "42" => "Merchant completed the order", "54" => "Merchant declined the order", "33" => "Merchant is editting the order", "29" => "Merchant is unavailable", "24" => "Looking for a Joey", "23" => "Waiting for merchant(s) to accept", "28" => "Order is with Joey", "133" => "Packages sorted", "55" => "ONLINE PAYMENT EXPIRED", "12" => "ONLINE PAYMENT FAILED", "53" => "Waiting for customer to pay", "141" => "Lost package", "60" => "Task failure","255" => 'Order Delay',
        "145" => 'Returned To Merchant',
        "146" => "Delivery Missorted, Incorrect Address",
        '147' => 'Scanned at Hub',
        '148' => 'Scanned at Hub and labelled',
        '149' => 'pick from hub',
        '150' => 'drop to other hub', '153' => 'Miss sorted to be reattempt', '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow','155' => 'To be re-attempted tomorrow');

    public function statusmap($id)
    {
        $statusid = array("136" => "Client requested to cancel the order",
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
            "110" => "Returned to hub for re-delivery",
            "111" => "Delivery to hub for return to merchant",
            "121" => "Out for delivery",
            "102" => "Joey Incident",
            "104" => "Damaged on road - delivery will be attempted",
            "105" => "Item damaged - returned to merchant",
            "129" => "Joey at hub",
            "128" => "Package on the way to hub",
            "140" => "Delivery missorted, may cause delay",
            "116" => "Successful delivery to neighbour",
            "132" => "Office closed - safe dropped",
            "101" => "Joey on the way to pickup",
            "32" => "Order accepted by Joey",
            "14" => "Merchant accepted",
            "36" => "Cancelled by JoeyCo",
            "124" => "At hub - processing",
            "38" => "Draft",
            "18" => "Delivery failed",
            "56" => "Partially delivered",
            "17" => "Delivery success",
            "68" => "Joey is at dropoff location",
            "67" => "Joey is at pickup location",
            "13" => "At hub - processing",
            "16" => "Joey failed to pickup order",
            "57" => "Not all orders were picked up",
            "15" => "Order is with Joey",
            "112" => "To be re-attempted",
            "131" => "Office closed - returned to hub",
            "125" => "Pickup at store - confirmed",
            "61" => "Scheduled order",
            "37" => "Customer cancelled the order",
            "34" => "Customer is editting the order",
            "35" => "Merchant cancelled the order",
            "42" => "Merchant completed the order",
            "54" => "Merchant declined the order",
            "33" => "Merchant is editting the order",
            "29" => "Merchant is unavailable",
            "24" => "Looking for a Joey",
            "23" => "Waiting for merchant(s) to accept",
            "28" => "Order is with Joey",
            "133" => "Packages sorted",
            "55" => "ONLINE PAYMENT EXPIRED",
            "12" => "ONLINE PAYMENT FAILED",
            "53" => "Waiting for customer to pay",
            "141" => "Lost package",
            "60" => "Task failure",
            "255" => 'Order Delay',
            "145" => 'Returned To Merchant',
            "146" => "Delivery Missorted, Incorrect Address",
            '147' => 'Scanned at Hub',
            '148' => 'Scanned at Hub and labelled',
            '149' => 'pick from hub',
            '150' => 'drop to other hub',
            '153' => 'Miss sorted to be reattempt',
            '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
            '155' => 'To be re-attempted tomorrow');
        return $statusid[$id];
    }

    public function getWalmart(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        return backend_view('walmart.walmart_dashboard');
    }

    public function walmartData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Walmart::where('created_at', 'like', $today_date . "%");
        return $datatables->eloquent($query)->setRowId(static function ($record) {
            return $record->id;
        })->editColumn('status_id', static function ($record) {
            return self::$status[$record->status_id];
        })->addColumn('action', static function ($record) {
            return backend_view('walmart.action', compact('record'));
        })->make(true);
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
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $walmart_data = Walmart::where('created_at', 'like', $date . "%")->get();
        $walmart_array[] = array('Store Name', 'Walmart Order #', 'Joey Name', 'Address', 'Schedule Pickup', 'Drop Off Eta', 'Status');
        foreach ($walmart_data as $walmart) {
            $walmart_array[] = array('Store Name' => $walmart->store_name, 'Walmart Order #' => $walmart->walmart_order_num, 'Joey Name' => $walmart->joey_name, 'Address' => $walmart->address, 'Schedule Pickup' => $walmart->schedule_pickup, 'Drop Off Eta' => $walmart->dropoff_eta, 'Status' => self::$status[$walmart->status_id]);
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
        $date = ($request->date != null) ? $request->date : date('Y-m-d');

        return backend_view('walmart.statistics_walmart_dashboard', compact('date'));
    }

    public function ajax_render_otd_charts(Request $request)
    {
        $date = $request->get('date');
        $query = "select store_name,store_name.store_num as store_num,
                    count(distinct sprint__tasks.sprint_id) as orders,
                    count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                    group by store_name.store_num order by store_name.store_num";
        $wmorders = DB::select($query);
//dd($wmorders);
        $walmartcounts = DB::select("select 
                    count(distinct(case when (CONVERT_TZ
                    (order_assigned_code.created_at,'UTC','America/Toronto') like '" . $date . "%') and (from_unixtime(due_time+5700)<sprint__tasks_history.created_at) then order_assigned_code.sprint_id else null end)) as wmlates,
                    count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
                    (order_assigned_code.created_at,'UTC','America/Toronto') between '" . date('y-m-d H:i:s', strtotime('-6 day', strtotime($date))) . "' and '" . $date . "') then order_assigned_code.sprint_id else null end)) as wmwlates,
                    count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
                    (order_assigned_code.created_at,'UTC','America/Toronto') between '" . date('y-m-d H:i:s', strtotime('-1 month', strtotime($date))) . "' and '" . $date . "') then order_assigned_code.sprint_id else null end)) as wmmlates,
                    count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
                    (order_assigned_code.created_at,'UTC','America/Toronto') between '2020-01-01' and '" . $date . "') then order_assigned_code.sprint_id else null end)) as wmylates
                    from order_code join order_assigned_code on(code_id=order_code.id)
                    join sprint__tasks on(sprint__tasks.sprint_id=order_assigned_code.sprint_id and type='pickup') 
                    join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
                    join sprint__sprints on(sprint__tasks_history.sprint_id=sprint__sprints.id and sprint__sprints.status_id=17)
                    where code_num=1");

        $totalcount = 0;
        $totallates = 0;
        if (!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $totalcount += $wmorder->orders;
                $wmstores[] = $wmorder->store_name;
                $deleiveries[] = $wmorder->orders;
                $lates[] = $wmorder->lates;
                $totallates += $wmorder->lates;
                $performance[] = 100 - (($wmorder->lates * 100) / $wmorder->orders);
            }

            $odt_data_1 = ['y1' => round((($totalcount - $walmartcounts[0]->wmlates) / $totalcount) * 100, 0), 'y2' => 100 - round((($totalcount - $walmartcounts[0]->wmlates) / $totalcount) * 100, 0)];
            $odt_data_2 = ['y1' => array_sum($performance) / count($performance), 'y2' => 100 - (array_sum($performance) / count($performance))];

        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
            $odt_data_2 = ['y1' => 100, 'y2' => 0];
        }

        return response()->json(array('status' => true, 'for' => 'pie_chart', 'data' => [$odt_data_1, $odt_data_2]));
    }

    public function ajax_render_short_summary(Request $request)
    {
        $date = $request->get('date');
        $walmartcounts = DB::select("select
            count(distinct(case when (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') like '" . $date . "%') and (from_unixtime(due_time+5700)<sprint__tasks_history.created_at) then order_assigned_code.sprint_id else null end)) as wmlates,
            count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') between '" . date('y-m-d H:i:s', strtotime('-6 day', strtotime($date))) . "' and '" . $date . "') then order_assigned_code.sprint_id else null end)) as wmwlates,
            count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') between '" . date('y-m-d H:i:s', strtotime('-1 month', strtotime($date))) . "' and '" . $date . "') then order_assigned_code.sprint_id else null end)) as wmmlates,
            count(distinct(case when (order_assigned_code.created_at > '2019-12-31') and (CONVERT_TZ
            (order_assigned_code.created_at,'UTC','America/Toronto') between '2020-01-01' and '" . $date . "') then order_assigned_code.sprint_id else null end)) as wmylates
            from order_code join order_assigned_code on(code_id=order_code.id)
            join sprint__tasks on(sprint__tasks.sprint_id=order_assigned_code.sprint_id and type='pickup') 
            join sprint__tasks_history on(sprint__tasks.sprint_id=sprint__tasks_history.sprint_id and sprint__tasks_history.status_id=68)
            join sprint__sprints on(sprint__tasks_history.sprint_id=sprint__sprints.id and sprint__sprints.status_id=17)
            where code_num=1");
        $query3 = "select 
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') like '" . $date . "%') and (sprint__tasks_history.status_id=67) then 1 else null end) as arrivals,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') like '" . $date . "%') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as ota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('y-m-d H:i:s', strtotime('-6 day', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as wdel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as wlates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=67)  then 1 else null end) as wa,            
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-6 day', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as wota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as mdel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as mlates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=67)   then 1 else null end) as ma,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 month', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as mota,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=68) then 1 else null end) as ydel,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=68) and (from_unixtime(due_time+5700)>sprint__tasks_history.created_at)  then 1 else null end) as ylates,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as ya,
            count(case when (sprint__sprints.created_at > '2019-12-31') and (CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') between '" . date('Y-m-d H:i:s', strtotime('-1 year', strtotime($date))) . "' and '" . $date . " 23:59:00') and (sprint__tasks_history.status_id=67) and (from_unixtime(due_time+1200)>sprint__tasks_history.created_at)  then 1 else null end) as yota
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
            where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' 
            and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
            group by store_name.store_num order by store_name.store_num";

        $wmorders = DB::select($query);


        $totalcount = 0;
        $totallates = 0;
        $performance = [];
        if (!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $totalcount += $wmorder->orders;
                $performance[] = 100 - (($wmorder->lates * 100) / $wmorder->orders);
            }

        }
        //return view('backend.walmart.sub-views.ajax-render-view-otd-pichart-one');
        $html = view('backend.walmart.sub-views.ajax-render-view-short-summary', compact('totalcount', 'performance', 'wmorders', 'overcounts', 'date', 'walmartcounts'))->render();
        return response()->json(array('status' => true, 'for' => 'short-summary', 'html' => $html));
    }

    public function ajax_render_walmart_order(Request $request)
    {
        $date = $request->get('date');
        $query = "select store_name,store_name.store_num as store_num,
                    count(distinct sprint__tasks.sprint_id) as orders,
                    count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                    group by store_name.store_num order by store_name.store_num";
        $wmorders = DB::select($query);
        $totalcount = 0;
        $totallates = 0;
        if (!empty($wmorders)) {
            foreach ($wmorders as $wmorder) {
                $totalcount += $wmorder->orders;
                $wmstores[] = $wmorder->store_name;
                $deleiveries[] = $wmorder->orders;
                $lates[] = $wmorder->lates;
                $totallates += $wmorder->lates;
                $performance[] = 100 - (($wmorder->lates * 100) / $wmorder->orders);
            }

            $data = ['categories' => $wmstores, 'data_set_one' => $deleiveries, 'data_set_two' => $lates,];

            return response()->json(array('status' => true, 'for' => 'walmart-orders', 'data' => $data));

        } else {
            return response()->json(array('status' => false, 'for' => 'walmart-orders', 'data' => []));
        }

    }

    public function ajax_render_walmart_on_time_orders(Request $request)
    {
        $date = $request->get('date');
        $query = "select store_name
                    from sprint__tasks
                    join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
                    join store_name on(store_name.store_num=sprint__sprints.store_num) 
                    where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' 
                    and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                    group by store_name.store_num order by store_name.store_num";
        $wmorders = DB::select($query);
        $totalcount = 0;
        $totallates = 0;
        if (!empty($wmorders)) {
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
                where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' and sprint__sprints.deleted_at IS NULL and sprint__sprints.status_id=17 
                and sprint__sprints.active=0
                and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503) 
                and type='pickup' and store_num IS NOT NULL group by store_num order by store_num";

        $otadata = DB::select($query5);
        $otas = [];
        $otds = [];
        $data = ['categories' => [], 'data_set_one' => [], 'data_set_two' => [],];
        if (!empty($otadata)) {
            foreach ($otadata as $data) {

                if ($data->arr != 0) {
                    $otas[] = round(($data->ota / $data->arr) * 100, 0);
                }

                if ($data->del != 0) {
                    $otds[] = round(($data->otd / $data->del) * 100, 0);
                }
            }

            $data = ['categories' => $wmstores, 'data_set_one' => $otas, 'data_set_two' => $otds,];
            return response()->json(array('status' => true, 'for' => 'walmart-on-time-orders', 'data' => $data));
        } else {

            return response()->json(array('status' => false, 'for' => 'walmart-on-time-orders', 'data' => $data));
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
        $page = ($request->page < 1) ? 1 : $request->page;
        $Records_Per_page = 10;
        $offset_value = ($page - 1) * $Records_Per_page;
        $total_count = 0;

        $query = "SELECT
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
                AND sprint__tasks_history.date > DATE_SUB('" . $date . "', INTERVAL 2 DAY))
                LEFT JOIN joeys ON (sprint__sprints.joey_id = joeys.id)
                WHERE TYPE = 'pickup'
                AND sprint__sprints.creator_id IN (476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
                AND sprint__sprints.status_id NOT IN (35, 36, 37, 38)
                AND sprint__sprints.deleted_at IS NULL
                AND CONVERT_TZ( FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') LIKE '" . $date . "%'
                AND sprint__tasks.created_at > DATE_SUB('" . $date . "', INTERVAL 2 DAY)
                AND sprint__sprints.created_at > DATE_SUB('" . $date . "', INTERVAL 2 DAY)
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
                AND sprint__tasks.created_at > DATE_SUB('" . $date . "', INTERVAL 2 DAY)
                AND merchantids.created_at > DATE_SUB('" . $date . "', INTERVAL 2 DAY)
                ) b
                ON (a.id = b.sprint_id)
                GROUP BY a.id";


        $fullrecord = DB::select($query);

        $total_pages = ($total_count > $Records_Per_page) ? $total_count / $Records_Per_page : 1;
        $html = view('backend.walmart.sub-views.ajax-render-view-walmart-total-orders-summary', compact('fullrecord', 'date', 'page', 'total_pages'))->with('status', self::$status)->render();
        return response()->json(array('status' => true, 'for' => 'total-orders-summary', 'html' => $html));
    }

    public function ajax_render_walmart_stores_data(Request $request)
    {
        $date = $request->date;
        $query = "select store_name,store_name.store_num as store_num,
        count(distinct sprint__tasks.sprint_id) as orders,
        count(case when from_unixtime(due_time+5700)<(select created_at from sprint__tasks_history where sprint_id=sprint__tasks.sprint_id and status_id=68 limit 1) then 1 else null end) as lates
        from sprint__tasks
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id) 
        join store_name on(store_name.store_num=sprint__sprints.store_num) 
        where type='pickup' and CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' 
        and sprint__sprints.status_id in(17,113,114) and sprint__sprints.active=0 and store_name.deleted_at IS NULL and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503)
        group by store_name.store_num order by store_name.store_num";

        $query2 = "select AVG(TIMESTAMPDIFF(SECOND,A.created_at,B.created_at)) as waits from
        (select distinct sprint__tasks.sprint_id,dep.created_at 
        from sprint__tasks_history as dep 
        join sprint__tasks on(sprint__tasks.sprint_id=dep.sprint_id) 
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id)
        where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' and dep.status_id=67 and type='pickup' and sprint__tasks.status_id!=38 
        and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503) and sprint__sprints.status_id=17 order by sprint__sprints.id) as A join
        (select distinct sprint__tasks.sprint_id,dep.created_at,store_num 
        from sprint__tasks_history as dep 
        join sprint__tasks on(sprint__tasks.sprint_id=dep.sprint_id) 
        join sprint__sprints on(sprint__sprints.id=sprint__tasks.sprint_id)
        where CONVERT_TZ(FROM_UNIXTIME(sprint__tasks.due_time),'UTC','America/Toronto') like '" . $date . "%' and dep.status_id=15 and type='pickup' and sprint__tasks.status_id!=38 
        and creator_id in(476734,475761,476610,476734,476850,476867,476933,476867,476967,476968,476969,476970,477006,477068,477069,477078,477123,477124,477150,477153,477154,477157,477192,477267,477268,477279,477503) and sprint__sprints.status_id=17 order by sprint__sprints.id) as B 
        on(A.sprint_id=B.sprint_id)
        where store_num IS NOT NULL GROUP BY store_num order by store_num";

        $waittimes = DB::select($query2);
        $jocodes = DB::select("select id,code from order_code where deleted_at IS NULL and code_num=1");
        $wmorders = DB::select($query);
        $wmcodes = DB::select("select id,code from order_code where deleted_at IS NULL and code_num=0");

        $html = view('backend.walmart.sub-views.ajax-render-view-walmart-stores-data', compact('wmorders', 'date', 'waittimes', 'jocodes', 'wmcodes'))->render();
        return response()->json(array('status' => true, 'for' => 'walmart-stores-data', 'html' => $html));
    }


    public function getWmExport(Request $request)
    {

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

        foreach ($response as $record) {

            echo "CR-" . $record->order_id . "\t";

            if (!empty($record->walmart_order_num)) echo trim(preg_replace('/\s+/', ' ', $record->walmart_order_num));
            echo "\t";

            echo $record->date . "\t";

            if (!empty($record->address)) echo stripcslashes($record->address) . "\t";
            if (!empty($record->store_num)) echo $record->store_num;
            echo "\t";

            echo $record->joey_name . "\t";
            echo $record->schedule . "\t";
            echo $record->compliant_pick . "\t";
            echo $record->compliant_drop . "\t";
            if (strtotime($record->arrival) > strtotime('2000-01-01 00:00:00')) {
                echo $record->arrival . "\t";
            } else {
                echo "\t";
            }

            if (!empty($record->departure) && !empty($record->arrival)) {
                if (strtotime($record->arrival) > strtotime('2000-01-01 00:00:00')) {
                    $date1 = date_create($record->arrival);
                    $date2 = date_create($record->departure);
                    $diff = date_diff($date1, $date2);
                    echo $diff->format("%h:%i:%s");
                }
            }
            echo "\t";

            $gracecompliant = "20" . date("y-m-d H:i:s", strtotime($record->compliant_pick . " +5 minutes"));
            if ($gracecompliant >= $record->arrival) echo "True\t"; else echo "False\t";

            if (strtotime($record->departure) > strtotime('2000-01-01 00:00:00')) {
                echo $record->departure . "\t";
            } else {
                echo "\t";
            }

            $delivery = $record->deliver;

            echo $record->deliver . "\t";
            // if (strtotime($record->deliver) > strtotime('2000-01-01 00:00:00')) {
            // echo $delivery."\t";
            // }
            // else {
            // echo "\t";
            // }

            $windowexp = "20" . date("y-m-d H:i:s", strtotime($record->compliant_drop) + 300);
            echo $windowexp . "\t";

            if ($windowexp >= $delivery) echo "True\t"; else echo "False\t";

            if (!empty($record->departure)) {
                if (strtotime($record->deliver) > strtotime('2020-01-01 00:00:00')) {
                    $date1 = date_create($record->departure);
                    $date2 = date_create($delivery);
                    $dur = date_diff($date2, $date1);
                    echo $dur->format("%i:%s");
                }
            }
            echo "\t";

            if (!empty($record->distance)) echo $record->distance . "km\t";
            if (!empty($record->note)) echo $record->note;
            echo "\t";

            $codes = OrderCode::join('order_assigned_code', 'code_id', '=', 'order_code.id')->where('sprint_id', '=', $record->order_id)->get();
            if (!empty($codes)) {
                foreach ($codes as $code) {
                    echo $code->code . ",";
                }
                echo "\t";
            }
            echo "\n";
        }

    }

    public function walmartNewCount()
    {
        $current_date = date("Y-m-d H:i:s");
        $pervious_date = date('Y-m-d H:i:s', strtotime('-20 seconds', strtotime(date("Y-m-d H:i:s"))));
        $pervious_count = \Illuminate\Support\Facades\DB::table('sprint__sprints')->whereIn('creator_id', [476734, 475761, 476610, 476734, 476850, 476867, 476933, 476867, 476967, 476968, 476969, 476970, 477006, 477068, 477069, 477078, 477123, 477124, 477150, 477153, 477154, 477157, 477192, 477267, 477268, 477279, 477503])->where('created_at', '<=', $pervious_date)->count();
        $new_count = \Illuminate\Support\Facades\DB::table('sprint__sprints')->whereIn('creator_id', [476734, 475761, 476610, 476734, 476850, 476867, 476933, 476867, 476967, 476968, 476969, 476970, 477006, 477068, 477069, 477078, 477123, 477124, 477150, 477153, 477154, 477157, 477192, 477267, 477268, 477279, 477503])->where('created_at', '<=', $current_date)->count();
        return $new_count - $pervious_count;

    }

    public function download_walmart_report_csv_view()
    {
        return backend_view('walmart.walmart_orders_report_view');
    }


    public function generate_walmart_report_csv(WalmartRequest $request)
    {
        // getting date from request
        $from_date = !empty($request->fromdatepicker) ? $request->fromdatepicker . " 00:00:00" : date("Y-m-d 00:00:00");
        $to_date = !empty($request->todatepicker) ? $request->todatepicker . " 23:59:59" : date("Y-m-d 23:59:59");

        // getting limit
        $limit = ($request->limit) ? (int)$request->limit : 15;

        // creating metaData
        $metaData = $request->all();

        // creatting file name if not exsit in request
        $file_name = (isset($metaData['file_name'])) ? $metaData['file_name'] : 'Walmart Orders Report ' . $request->fromdatepicker . ' to ' . $request->todatepicker . ' ' . date('Y-m-d_H_i_s_u') . '.csv';

        // update metaData with file name
        $metaData['file_name'] = $file_name;

        // creating file path
        $path = public_path() . '/dashboard-reports/' . $file_name;

        //creating download path
        $metaData['downloadPath'] = url('/dashboard-reports/' . $file_name);

        // creating csv header
        $csv_header = ["Order Id", "Walmart Order Number", "Date", "Address", "Store Name", "Driver Name", "Schedule Pickup", "Compliant Pickup", "Compliant Dropoff", "Driver Arrival", "Waiting Time", "Pickup Compliant", "Driver Departure", "Delivery To Customer", "Window Expiration", "Compliant Walmart", "Order Pickup - Dropoff Duration", "Distance", "Reason", "Codes"];

        // open or create file
        $file = fopen($path, 'a');

        // add header file on new file
        if ($request->file_name == null) {
            fputcsv($file, $csv_header);
        }

        //checking the filter sprint ids exsit or not
        /*if(!isset($metaData['filter_ids']))
        {*/
        // getting walmart vendors ids
        $walmart_vendors_ids = WalmartStoreVendors::pluck('vendor_id')->toArray();

        // getting sprint ids from tasks table
        $sprint_ids = Task::join('sprint__sprints', 'sprint__tasks.sprint_id', '=', 'sprint__sprints.id')->whereBetween(DB::raw("CONVERT_TZ(FROM_UNIXTIME(due_time),'UTC','America/Toronto')"), [$from_date, $to_date])->whereIn('sprint__sprints.creator_id', $walmart_vendors_ids)->where('sprint__tasks.type', 'dropoff')->whereNotIn('sprint__sprints.status_id', [35, 36, 37, 38])->pluck('sprint_id');

        // setting query filter sprint ids in meta data for not execute query again  ids
        //$metaData['filter_ids'] = $sprint_ids;
        //}
        //else
        //{
        // getting already filter
        //$sprint_ids = $metaData['filter_ids'];

        //}
        // getting sprint data
        $sprint_data = Sprint::whereIn('id', $sprint_ids)->where('deleted_at', null)->orderBy('id', 'ASC')->paginate($limit);

        //sending total records
        $metaData['total_records'] = $sprint_data->total();

        $responce = [];

        foreach ($sprint_data as $key => $sprint) {

            $joey_name = (isset($sprint->JoeyObject)) ? $sprint->JoeyObject->full_name : '';
            $schedule = (isset($sprint->SprintFirstPickUpTask->DueTimeDateTimeFormat)) ? date('Y-m-d H:i:s', strtotime($sprint->SprintFirstPickUpTask->DueTimeDateTimeFormat . ' +30 minutes')) : '';
            $compliant_pickup = (isset($sprint->SprintFirstPickUpTask->DueTimeDateTimeFormat)) ? date('Y-m-d H:i:s', strtotime($sprint->SprintFirstPickUpTask->DueTimeDateTimeFormat . ' +15 minutes')) : '';
            $compliant_dropoff = (isset($sprint->SprintFirstPickUpTask->DueTimeDateTimeFormat)) ? date('Y-m-d H:i:s', strtotime($sprint->SprintFirstPickUpTask->DueTimeDateTimeFormat . ' +90 minutes')) : '';
            // checking arrival date time
            $arrival = $sprint->SprintTaskHistory->where('status_id', 67)->where('active', 1)->min('created_at');
            if (!empty($arrival)) {
                $arrival = ConvertTimeZone($arrival, 'UTC', 'America/Toronto', 'Y-m-d H:i:s');
            }

            // checking departure date time
            $departure = $sprint->SprintTaskHistory->where('status_id', 15)->max('resolve_time');
            if ($departure) {
                $departure = ConvertTimeZone($departure, 'UTC', 'America/Toronto');
            }

            // checking $deliver date time
            $deliver = $sprint->SprintTaskHistory->where('status_id', 17)->max('resolve_time');
            if ($deliver) {
                $deliver = ConvertTimeZone($deliver, 'UTC', 'America/Toronto');
            }

            // calculating waiting
            $wating_time = '';
            if (!empty($departure) && !empty($arrival)) {
                $date1 = date_create($arrival);
                $date2 = date_create($departure);
                $diff = date_diff($date1, $date2);
                $wating_time = $diff->format("%h:%i:%s");
            }

            // checkikng pickup time is grater then arrival time
            $pickup_compliant_time = date("Y-m-d H:i:s", strtotime($compliant_pickup . " +5 minutes"));
            $pickup_compliant = ($pickup_compliant_time >= $arrival) ? 'True' : 'False';

            // calculatoin Window Expiration
            $window_expiration = date('Y-m-d H:i:s', strtotime($compliant_dropoff . ' +5 minutes'));

            //checking is Compliant to Walmart
            $is_compliant_walmart = ($window_expiration >= $deliver) ? 'True' : 'False';

            $Order_Pickup_time_difference = '';

            if (!empty($departure) && !empty($deliver)) {
                $date1 = date_create($departure);
                $date2 = date_create($deliver);
                $diff = date_diff($date2, $date1);
                $Order_Pickup_time_difference = $diff->format("%h:%i:%s");
            }

            // converting metter into km
            $distance = ($sprint->distance > 0) ? ($sprint->distance / 1000) . 'km' : 0;

            // getting nots
            $notes = (isset($sprint->SprintNotes->note)) ? $sprint->SprintNotes->implode('note', ' . ') : '';

            // getting orders codes
            $order_cods = (count($sprint->OrderCodes)) ? $sprint->OrderCodes->implode('code', ' . ') : '';

            // getting merchant order num
            $merchant_order_num = (isset($sprint->SprintLastDropOffTask->Merchantids)) ? $sprint->SprintLastDropOffTask->Merchantids->merchant_order_num : '';
            $csv_row = ["CR-" . $sprint->id, $merchant_order_num, ConvertTimeZone($sprint->created_at, 'UTC', 'America/Toronto'), stripcslashes($sprint->SprintLastDropOffTask->task_Location->address), $sprint->store_num, $joey_name, $schedule, $compliant_pickup, $compliant_dropoff, $arrival, $wating_time, $pickup_compliant, $departure, $deliver, $window_expiration, $is_compliant_walmart, $Order_Pickup_time_difference, $distance, $notes, $order_cods];


            fputcsv($file, $csv_row);

        }

        return RestAPI::setPagination($sprint_data)->response([], 200, '', $metaData);


    }

    public function get_trackingorderdetails($sprintId)
    {
        $result = Sprint::join('sprint__tasks', 'sprint_id', '=', 'sprint__sprints.id')
            ->leftJoin('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('joey_route_locations', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('joey_routes', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->leftJoin('joeys', 'joeys.id', '=', 'joey_routes.joey_id')
            ->join('locations', 'sprint__tasks.location_id', '=', 'locations.id')
            ->join('sprint__contacts', 'contact_id', '=', 'sprint__contacts.id')
            ->leftJoin('vendors', 'creator_id', '=', 'vendors.id')
            ->where('sprint__tasks.sprint_id', '=', $sprintId)
            ->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal', 'DESC')->take(1)
            ->get(array('sprint__tasks.*', 'joey_routes.id as route_id',\DB::raw("CONVERT_TZ(joey_routes.date,'UTC','America/Toronto') as route_date"), 'locations.address', 'locations.suite', 'locations.postal_code', 'sprint__contacts.name', 'sprint__contacts.phone', 'sprint__contacts.email',
                'joeys.first_name as joey_firstname', 'joeys.id as joey_id',
                'joeys.last_name as joey_lastname', 'vendors.name as merchant_name', 'vendors.first_name as merchant_firstname', 'vendors.last_name as merchant_lastname', 'merchantids.scheduled_duetime'
            , 'joeys.id as joey_id', 'merchantids.tracking_id', 'joeys.phone as joey_contact', 'joey_route_locations.ordinal as stop_number', 'merchantids.merchant_order_num', 'merchantids.address_line2', 'sprint__sprints.creator_id'));

        $i = 0;

        $data = [];

        foreach ($result as $tasks) {
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] = $tasks;
            $taskHistory = TaskHistory::where('sprint_id', '=', $tasks->sprint_id)->WhereNotIn('status_id', [17, 38,0])->orderBy('date')
                //->where('active','=',1)
                ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id', '=', $tasks->sprint_id)->orderBy('created_at')
                ->first();

            if (!empty($returnTOHubDate)) {
                $taskHistoryre = TaskHistory::where('sprint_id', '=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id', [17, 38,0])->orderBy('date')
                    //->where('active','=',1)
                    ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

                foreach ($taskHistoryre as $history) {

                    $status[$history->status_id]['id'] = $history->status_id;
                    if ($history->status_id == 13) {
                        $status[$history->status_id]['description'] = 'At hub - processing';
                    } else {
                        $status[$history->status_id]['description'] = $this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = $history->created_at;

                }

            }
            if (!empty($returnTOHubDate)) {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id', '=', $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if (!empty($returnTO2)) {
                    $taskHistoryre = TaskHistory::where('sprint_id', '=', $returnTO2->reattempt_of)->WhereNotIn('status_id', [17, 38,0])->orderBy('date')
                        //->where('active','=',1)
                        ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

                    foreach ($taskHistoryre as $history) {

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if ($history->status_id == 13) {
                            $status2[$history->status_id]['description'] = 'At hub - processing';
                        } else {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = $history->created_at;

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history) {
                if (in_array($history->status_id, [61,13]) or in_array($history->status_id, [124,125])) {
                    $status1[$history->status_id]['id'] = $history->status_id;

                    if ($history->status_id == 13) {
                        $status1[$history->status_id]['description'] = 'At hub - processing';
                    } else {
                        $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                    }
                    $status1[$history->status_id]['created_at'] = $history->created_at;
                }
                else{
                    if ($history->created_at >= $tasks->route_date){
                        $status1[$history->status_id]['id'] = $history->status_id;

                        if ($history->status_id == 13) {
                            $status1[$history->status_id]['description'] = 'At hub - processing';
                        } else {
                            $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status1[$history->status_id]['created_at'] = $history->created_at;
                    }
                }
            }

            if ($status != null) {
                $sort_key = array_column($status, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status);
            }
            if ($status1 != null) {
                $sort_key = array_column($status1, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status1);
            }
            if ($status2 != null) {
                $sort_key = array_column($status2, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status2);
            }


            $data[$i]['status'] = $status;
            $data[$i]['status1'] = $status1;
            $data[$i]['status2'] = $status2;
            $i++;
        }


        return ['data' => $data, 'sprintId' => $sprintId];
        // return backend_view('orderdetailswtracknigid',['data'=>$data,'sprintId' => $sprintId,'reasons' => $reasons]);
    }


    public function getWalmartEcommerce(Request $request)
    {

        $city = !empty($request->get('city')) ? $request->get('city') : 'all';
        $status_code = array_intersect_key(self::$status, [61 => '', 124 => '', 121 => '', 133 => '', 17 => '', 113 => '', 114 => '', 116 => '', 117 => '', 118 => '', 132 => '', 138 => '', 139 => '', 144 => '', 104 => '', 105 => '', 106 => '', 107 => '', 108 => '', 109 => '', 110 => '', 111 => '', 112 => '', 131 => '', 135 => '', 136 => '']);
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        return backend_view('walmart-ecomerce.e_commerce_dashboard', compact('city', 'status_code'));
    }

    public function WalmartEcommerceData(Datatables $datatables, Request $request)
    {


        $sprintId = 0;
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $city_data = $request->city;

        $start_dt = new DateTime($today_date . " 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date . " 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        if (!empty($request->get('tracking_id'))) {
            $task_id = MerchantIds::where('tracking_id', $request->get('tracking_id'))->where('deleted_at', null)->orderBy('id', 'desc')->first();
            if ($task_id) {
                $sprint = Task::where('id', $task_id->task_id)->first();
                $sprintId = $sprint->sprint_id;
            }
        }
        //dd($sprintId);
        if (!empty($request->get('route_id'))) {
            $task_ids = JoeyRouteLocations::where('route_id', $request->get('route_id'))->where('deleted_at', null)->pluck('task_id');

            if ($task_ids) {
                $sprintIds = Task::whereIn('id', $task_ids)->pluck('sprint_id');
            }
        }
        if (!empty($request->get('tracking_id'))) {
            $query = BoradlessDashboard::where('sprint_id', $sprintId)->where('deleted_at', null);
        } else if (!empty($request->get('route_id'))) {
            $query = BoradlessDashboard::whereIn('sprint_id', $sprintIds)->where('deleted_at', null);
        } else {
            // $ctcVendorIds = CtcVendor::pluck('vendor_id');
            $query = BoradlessDashboard::where('created_at', '>', $start)->where('created_at', '<', $end)->whereNotIn('task_status_id', [38, 36]);
        }

        if (!empty($request->get('status'))) {
            $sprint_status = new Sprint();
            if ($request->get('status') == 1) {
                $statusIds = $sprint_status->getStatusCodes('competed');
            } elseif ($request->get('status') == 2) {
                $statusIds = $sprint_status->getStatusCodes('return');
            } else {
                $statusIds = [$request->get('status')];
            }

            $query = $query->whereIn('task_status_id', $statusIds);
        }
        if (!empty($city_data)) {
            if ($city_data == 'ottawa')
            {
                $query = $query->whereIn('creator_id', [477631,477629]);
            }
            elseif ($city_data == 'vancouver')
            {
                $query = $query->whereIn('creator_id', [477607,477589,477641]);
            }elseif ($city_data == 'toronto')
            {
                $query = $query->whereIn('creator_id', [477621,477587]);
            }
            else
            {
                $query = $query->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
            }

        }
        else
        {
            $query = $query->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
        }
        return $datatables->eloquent($query)->setRowId(static function ($record) {
            return $record->id;
        })->addColumn('sprint_id', static function ($record) {
            return $record->sprint_id ? $record->sprint_id : '';
        })->editColumn('task_status_id', static function ($record) {
            $current_status = $record->task_status_id;
            if ($record->task_status_id == 17) {
                $preStatus = SprintTaskHistory::where('sprint_id', '=', $record->sprint_id)->where('status_id', '!=', '17')->orderBy('id', 'desc')->first();
                if (!empty($preStatus)) {
                    $current_status = $preStatus->status_id;
                }
            }
            if ($current_status == 13) {
                return "At hub - processing";
            } else {
                return self::$status[$current_status];
            }
        })->addColumn('route_id', static function ($record) {
            return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
        })->addColumn('joey_name', static function ($record) {
            return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
        })->addColumn('tracking_id', static function ($record) {
            return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
        })->addColumn('eta_time', static function ($record) {
            if ($record->eta_time) {
                $eta_time = new DateTime(date('Y-m-d H:i:s', strtotime("+1 day", $record->eta_time)), new DateTimeZone('UTC'));
                $eta_time->setTimeZone(new DateTimeZone('America/Toronto'));
                return $eta_time->format('Y-m-d H:i:s');
            }
        })->addColumn('store_name', static function ($record) {
            return $record->store_name ? $record->store_name : '';
        })->addColumn('customer_name', static function ($record) {
            return $record->customer_name ? $record->customer_name : '';
        })->addColumn('weight', static function ($record) {
            return $record->weight ? $record->weight : '';
        })->addColumn('address_line_2', static function ($record) {

            // return $record->address_line_2 ? $record->address_line_2 : '';
            if(isset($record->address_line_1))
            {
                return $record->address_line_1;
            }
            elseif (isset($record->address_line_2))
            {
                return $record->address_line_2;
            }
            else
            {
                return $record->address_line_3 ? $record->address_line_3 : '';
            }

        })->addColumn('action', static function ($record) {
            return backend_view('walmart-ecomerce.e-commerce-action', compact('record'));
        })->make(true);

    }



    /**
     * Get walmart Dashboard Excel Report
     */
    public function walmartDashboardExcel($date = null, $vendor = null)
    {

        if ($date == null) {
            $date = date('Y-m-d');

        }
        $file_name = new \DateTime($date);
        $file_name = $file_name->format("M d, Y");
        $file_name = "Walmart Tracking File " . $file_name . ".csv";

        $ottawa=[477631,477629];
        $vancouver =[477607,477589,477641];
        $toronto = [477621,477587];

        $ctcVendors = CtcVendor::whereNotIn('vendor_id',[477340,477341,477342,477343,477344,477345,477346])->pluck('vendor_id')->toArray();
        if ($vendor == 'all-vendors')
        {
            $boradlessVendorIds = array_merge($ottawa,$vancouver,$toronto);
        }
        elseif ($vendor == 'ottawa')
        {
            $boradlessVendorIds = $ottawa;
        }
        elseif ($vendor == 'vancouver')
        {
            $boradlessVendorIds = $vancouver;
        }
        elseif ($vendor == 'toronto')
        {
            $boradlessVendorIds = $toronto;
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $boradless_data = BoradlessDashboard::whereIn('creator_id', $boradlessVendorIds)->where('created_at','>',$start)->where('created_at','<',$end)
            ->whereNotIn('task_status_id', [38, 36])->orderBy('id', 'desc')->get();
        //$boradless_data = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();
        // header info for browser


        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$file_name);
        header('Pragma: no-cache');
        header('Expires: 0');

        // echo "JoeyCo Order\tRoute\tJoey\tStore Name\tCustomer Name\tCustomer Address\tPostal Code\tCity Name\tWeight\tPickup From Store\t1st Attempt - At Hub Processing\t1st Attempt - Out For Delivery\t1st Attempt - Estimated Customer Delivery Time\t1st Attempt - Delivery\t1st Attempt - Shipment Delivery Status\t2nd Attempt - At Hub Processing\t2nd Attempt - Out For Delivery\t2nd Attempt - Estimated Customer Delivery Time\t2nd Attempt - Delivery\t2nd Attempt - Shipment Delivery Status\t3rd Attempt - At Hub Processing\t3rd Attempt - Out For Delivery\t3rd Attempt - Estimated Customer Delivery Time\t3rd Attempt - Delivery\t3rd Attempt - Shipment Delivery Status\tShipment Tracking #\tActual Delivery Status\tActual Delivery\tShipment Tracking Link\tJoyeCo Notes / Comments\t\n";
        echo "JoeyCo Order,Route,Joey,Store Name,Customer Name,Customer Address,Postal Code,City Name,Weight,Pickup From Store,1st Attempt - At Hub Processing,1st Attempt - Out For Delivery,1st Attempt - Estimated Customer Delivery Time,1st Attempt - Delivery,1st Attempt - Shipment Delivery Status,2nd Attempt - At Hub Processing,2nd Attempt - Out For Delivery,2nd Attempt - Estimated Customer Delivery Time,2nd Attempt - Delivery,2nd Attempt - Shipment Delivery Status,3rd Attempt - At Hub Processing,3rd Attempt - Out For Delivery,3rd Attempt - Estimated Customer Delivery Time,3rd Attempt - Delivery,3rd Attempt - Shipment Delivery Status,Shipment Tracking #,Actual Delivery Status,Actual Delivery,Shipment Tracking Link,JoyeCo Notes / Comments,\n";

        // $boradless_array[] = ['Joeyco Order', 'Route', 'Joey', 'Store Name', 'Customer Name', 'Customer Address', 'Postal Code', 'City Name', 'Weight', 'Pickup From Store', 'At Hub Processing', 'Out For Delivery', 'Estimated Customer delivery time', 'Actual Customer delivery time', 'Shipment tracking #', 'Shipment tracking link', 'Shipment Delivery Status', 'JoyeCo Notes / Comments', 'Returned to HUB 2', '2nd Attempt Pick up', '2nd Attempt Delivery', 'Returned to HUB 3', '3rd Attempt Pick up', '3rd Attempt Delivery'];

        foreach ($boradless_data as $boradless_rec) {

            $boradless = null;
            if ($boradless_rec->sprintReattempts) {
                if ($boradless_rec->sprintReattempts->reattempts_left == 0) {
                    $boradless =  $firstSprint = BoradlessDashboard::where('sprint_id', '=', $boradless_rec->sprintReattempts->reattempt_of)->first();
                }
                else
                {
                    $boradless = $boradless_rec;
                }
            }
            else
            {
                $boradless = $boradless_rec;
            }
            $pickup3 = "";
            $hubreturned3 = "";
            $hubpickup3 = "";
            $deliver3 = "";
            $eta_time3 = "";
            $status3 = "";
            $pickup2 = "";
            $hubreturned2 = "";
            $hubpickup2 = "";
            $deliver2 = "";
            $eta_time2 = "";
            $status2 = "";
            $notes = '';
            $check_actual = false;
            $pickup = $boradless->pickupFromStore()->pickup;
            $hubreturned = "";//$boradless->atHubProcessing()->athub;
            $hubpickup = "";// $boradless->outForDelivery()->outdeliver;
            $deliver = "";//$boradless->deliveryTime()->delivery_time;
            $actual_delivery = $boradless->actualDeliveryTime()->actual_delivery;
            $actual_delivery_status = '';

            $eta_time = "";
            if ($pickup) {
                $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
            }
            $status = $boradless->task_status_id;
            if ($boradless->task_status_id == 17) {
                $preStatus = \App\SprintTaskHistory::where('sprint_id', '=', $boradless->sprint_id)
                    ->where('status_id', '!=', '17')
                    ->orderBy('id', 'desc')->first();
                if (!empty($preStatus)) {
                    $status = $preStatus->status_id;
                }
            }
            if ($boradless->actualDeliveryTime()->actual_delivery != null) {
                $check_actual = true;
                $actual_delivery_status = $boradless->actualDeliveryTime()->status_id;

            }
            $notes1 = Notes::where('object_id', $boradless->sprint_id)->pluck('note');
            $i = 0;
            foreach ($notes1 as $note) {
                if ($i == 0)
                    $notes = $notes . $note;
                else
                    $notes = $notes . ', ' . $note;
            }
            if ($boradless->sprintReattempts) {
                if ($boradless->sprintReattempts->reattempts_left == 0) {

                    $hubreturned3 = $boradless->atHubProcessing()->athub;
                    $hubpickup3 = $boradless->outForDelivery()->outdeliver;
                    $deliver3 = $boradless->deliveryTime()->delivery_time;
                    if ($hubreturned3) {
                        $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                    }
                    $status3 = $boradless->task_status_id;
                    if ($boradless->task_status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $boradless->sprint_id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status3 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $boradless->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            if (in_array($secAttempt->status_id, [133])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = BoradlessDashboard::where('sprint_id', $boradless->sprintReattempts->reattempt_of)->first();
                            if ($hubreturned2) {
                                $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                            }
                            $status2 = isset($eta->task_status_id) ? $eta->task_status_id : '';
                            if ($status2) {
                                if ($eta->task_status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->sprint_id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status2 = $preStatus->status_id;
                                    }
                                }
                            }

                            if (in_array($secAttempt->status_id, [17,113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;

                                }
                            }

                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $boradless->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                /* if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 140, 110])) {
                                     $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                 }*/
                                $eta = BoradlessDashboard::where('sprint_id', $firstSprint->reattempt_of)->first();
                                if ($pickup) {
                                    $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                                }
                                $status2 = isset($eta->task_status_id) ? $eta->task_status_id : '';
                                if ($status2) {
                                    if ($eta->task_status_id == 17) {
                                        $preStatus = \App\SprintTaskHistory
                                            ::where('sprint_id', '=', $eta->sprint_id)
                                            ->where('status_id', '!=', '17')
                                            ->orderBy('id', 'desc')->first();
                                        if (!empty($preStatus)) {
                                            $status = $preStatus->status_id;
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
                if ($boradless->sprintReattempts->reattempts_left == 1) {

                    $hubreturned3 = $boradless->atHubProcessing()->athub;
                    $hubpickup3 = $boradless->outForDelivery()->outdeliver;
                    $deliver3 = $boradless->deliveryTime()->delivery_time;
                    if ($hubreturned3) {
                        $eta_time3 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned3))).' 21:00:00';
                    }
                    $status3 = $boradless->task_status_id;
                    if ($boradless->task_status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $boradless->sprint_id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status3 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $boradless->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')
                        ->get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {

                        foreach ($secondAttempt as $secAttempt) {

                            if (in_array($secAttempt->status_id, [133])) {
                                $hubreturned2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver2 = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = BoradlessDashboard::where('sprint_id', $boradless->sprintReattempts->reattempt_of)->first();
                            if ($hubreturned2) {
                                $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                            }
                            $status2 = isset($eta->task_status_id) ? $eta->task_status_id : '';
                            if ($status2) {
                                if ($eta->task_status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->sprint_id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status2 = $preStatus->status_id;
                                    }
                                }
                            }

                            if (in_array($secAttempt->status_id, [17,113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;

                                }
                            }

                        }
                    }

                    $firstSprint = \App\SprintReattempt::where('sprint_id', '=', $boradless->sprintReattempts->reattempt_of)->first();
                    if (!empty($firstSprint)) {
                        $firstAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $firstSprint->reattempt_of)->orderBy('created_at', 'ASC')->
                        get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                        if (!empty($firstAttempt)) {

                            foreach ($firstAttempt as $firstAttempt) {
                                if ($firstAttempt->status_id == 125) {
                                    $pickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [124])) {
                                    $hubreturned = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if ($firstAttempt->status_id == 121) {
                                    $hubpickup = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                    $deliver = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                }
                                /* if (in_array($firstAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 140, 110])) {
                                     $actual_delivery = date('20y-m-d H:i:s', strtotime($firstAttempt->created_at));
                                 }*/
                                $eta = BoradlessDashboard::where('sprint_id', $firstSprint->reattempt_of)->first();
                                if ($pickup) {
                                    $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                                }
                                $status = isset($eta->task_status_id) ? $eta->task_status_id : '';
                                if ($status) {
                                    if ($eta->task_status_id == 17) {
                                        $preStatus = \App\SprintTaskHistory
                                            ::where('sprint_id', '=', $eta->sprint_id)
                                            ->where('status_id', '!=', '17')
                                            ->orderBy('id', 'desc')->first();
                                        if (!empty($preStatus)) {
                                            $status = $preStatus->status_id;
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
                if ($boradless->sprintReattempts->reattempts_left == 2) {

                    $hubreturned2 = $boradless->atHubProcessing()->athub;
                    $hubpickup2 = $boradless->outForDelivery()->outdeliver;
                    $deliver2 = $boradless->deliveryTime()->delivery_time;

                    if ($hubreturned2) {
                        $eta_time2 = date('Y-m-d', strtotime("+1 day", strtotime($hubreturned2))).' 21:00:00';
                    }
                    $status2 = $boradless->task_status_id;
                    if ($boradless->task_status_id == 17) {
                        $preStatus = \App\SprintTaskHistory
                            ::where('sprint_id', '=', $boradless->sprint_id)
                            ->where('status_id', '!=', '17')
                            ->orderBy('id', 'desc')->first();
                        if (!empty($preStatus)) {
                            $status2 = $preStatus->status_id;
                        }
                    }

                    $secondAttempt = \App\SprintTaskHistory::where('sprint_id', '=', $boradless->sprintReattempts->reattempt_of)->orderBy('created_at', 'ASC')->
                    get(['status_id', \DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') as created_at")]);
                    if (!empty($secondAttempt)) {
                        date_default_timezone_set('America/Toronto');
                        foreach ($secondAttempt as $secAttempt) {
                            if ($secAttempt->status_id == 125) {
                                $pickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [124])) {
                                $hubreturned = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if ($secAttempt->status_id == 121) {
                                $hubpickup = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141])) {
                                $deliver = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                            }

                            $eta = BoradlessDashboard::where('sprint_id', $boradless->sprintReattempts->reattempt_of)->first();
                            if ($pickup) {
                                $eta_time = date('Y-m-d', strtotime("+1 day", strtotime($pickup))).' 21:00:00';
                            }
                            $status = isset($eta->task_status_id) ? $eta->task_status_id : '';
                            if (!empty($status)) {
                                if ($eta->task_status_id == 17) {
                                    $preStatus = \App\SprintTaskHistory
                                        ::where('sprint_id', '=', $eta->sprint_id)
                                        ->where('status_id', '!=', '17')
                                        ->orderBy('id', 'desc')->first();
                                    if (!empty($preStatus)) {
                                        $status = $preStatus->status_id;
                                    }
                                }
                            }
                            if (in_array($secAttempt->status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery = date('20y-m-d H:i:s', strtotime($secAttempt->created_at));
                                }
                            }
                            if (in_array($secAttempt->status_id, [113, 114, 116, 117, 118, 132, 138, 139, 144])) {
                                if (!$check_actual) {
                                    $actual_delivery_status = $secAttempt->status_id;
                                }
                            }
                        }
                    }
                }
            } else {
                $hubreturned = $boradless->atHubProcessingFirst()->athub;
                $hubpickup = $boradless->outForDelivery()->outdeliver;
                $deliver = $boradless->deliveryTime()->delivery_time;
            }

            echo $boradless->sprint_id . ",";

            if ($boradless->route_id) {
                echo 'R-' . $boradless->route_id . '-' . $boradless->ordinal . ",";
            } else {
                echo " " . ",";
            }

            if ($boradless->joey_name) {
                echo str_replace(",", "-", $boradless->joey_name . ' (' . $boradless->joey_id . ')') . ",";
            } else {
                echo "" . ",";
            }

            if ($boradless->store_name) {
                echo str_replace(",","-",$boradless->store_name ) . ",";
            } else {
                echo "" . ",";
            }

            if ($boradless->customer_name) {
                echo str_replace(",","-",$boradless->customer_name ) . ",";
            } else {
                echo "" . ",";
            }

            if(isset($boradless->address_line_1))
            {
                echo str_replace(",", "-", $boradless->address_line_1) . ",";
            }
            elseif (isset($boradless->address_line_2))
            {
                echo str_replace(",", "-", $boradless->address_line_2) . ",";
            }
            elseif(isset($boradless->address_line_3))
            {
                echo str_replace(",", "-", $boradless->address_line_3) . ",";
            }
            else
            {
                echo "" . ",";
            }

            if ($boradless->sprintBoradlessTasks) {
                if ($boradless->sprintBoradlessTasks->task_Location) {
                    echo str_replace(",","-",$boradless->sprintBoradlessTasks->task_Location->postal_code )  . ",";
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }
            if ($boradless->sprintBoradlessTasks) {
                if ($boradless->sprintBoradlessTasks->task_Location) {
                    if ($boradless->sprintBoradlessTasks->task_Location->city) {
                        echo str_replace(",","-",$boradless->sprintBoradlessTasks->task_Location->city->name )  . ",";
                    } else {
                        echo "" . ",";
                    }
                } else {
                    echo "" . ",";
                }
            } else {
                echo "" . ",";
            }

            if ($boradless->weight)
            {
                if ($boradless->weight)
                {
                    echo $boradless->weight . ",";
                }
                else
                {
                    echo "" . ",";
                }
            }
            else
            {
                echo "" . ",";
            }

            echo $pickup . ",";

            echo $hubreturned . ",";


            echo $hubpickup . ",";


            echo $eta_time . ",";


            echo $deliver . ",";

            if (!empty($status)) {
                echo ($status == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status])  . ",";
            } else {
                echo "" . ",";
            }
            echo $hubreturned2 . ",";
            echo $hubpickup2 . ",";
            echo $eta_time2 . ",";
            echo $deliver2 . ",";
            if (!empty($status2)) {
                echo ($status2 == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status2] ) . ",";
            } else {
                echo "" . ",";
            }
            echo $hubreturned3 . ",";
            echo $hubpickup3 . ",";
            echo $eta_time3 . ",";
            echo $deliver3 . ",";
            if (!empty($status3)) {
                echo ($status3 == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$status3] ) . ",";
            } else {
                echo "" . ",";
            }


            if ($boradless->tracking_id) {
                if (str_contains($boradless->tracking_id, 'old_')) {
                    echo substr($boradless->tracking_id, strrpos($boradless->tracking_id, '_') + 1) . ",";
                }
                else
                {
                    echo $boradless->tracking_id . ",";
                }
            } else {
                echo "" . ",";
            }
//            echo ($actual_delivery_status == 13) ? "At hub - processing"."\t" : self::$status[$actual_delivery_status] . "\t";
            if (!empty($actual_delivery_status)) {
                echo ($actual_delivery_status == 13) ? "At hub - processing" . "," : str_replace(",","-",self::$status[$actual_delivery_status])  . ",";
            } else {
                echo "" . ",";
            }
            echo $actual_delivery . ",";
            if ($boradless->tracking_id) {
                if (str_contains($boradless->tracking_id, 'old_')) {
                    echo "https://www.joeyco.com/track-order/" . substr($boradless->tracking_id, strrpos($boradless->tracking_id, '_') + 1) . ",";
                }
                else{
                    echo "https://www.joeyco.com/track-order/" .$boradless->tracking_id. ",";
                }
            } else {
                echo '' . ",";
            }


            echo $notes . ",";
            echo "\n";


        }

    }



    public function Ecommerce_Profile(Request $request, $id)
    {
        $commerce_data = $this->get_trackingorderdetails($id);

        $sprintId = $commerce_data['sprintId'];
        $data = $commerce_data['data'];
        return backend_view('walmart-ecomerce.e_commerce_profile', compact('data', 'sprintId'));
    }

    //Walmart E-Commerce Dashboard Function
    public function getWalmartEcommerceDashboard(Request $request)
    {
        $type = 'total';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('walmart-ecomerce.walmart_order_dashboard', compact( 'type','city'));
    }

    public function getWalmartEcommerceDashboardData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $city_data = $request->store_name;

        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($city_data == 'ottawa') {
            $ctcVendorIds = [477631,477629];
        } elseif ($city_data == 'vancouver') {
            $ctcVendorIds = [477607,477589,477641];
        } elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477621,477587];
        }
        else
        {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else
                {
                    return '';
                }

            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (isset($record->ExchangeRequestOrder))
                {
                    if (str_contains($record->tracking_id, 'old_'))
                    {
                        return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1).'<br><span class="label label-success">Exchange Request Order </span>';
                    }
                    else
                    {
                        return $record->tracking_id.'<br><span class="label label-success">Exchange Request Order </span>';
                    }
                }
                else
                {
                    if (str_contains($record->tracking_id, 'old_')) {
                        return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                    }
                    else
                    {
                        return $record->tracking_id;
                    }
                }

            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.order_action', compact('record'));
            })
            ->make(true);
    }

    public function getWalmartEcommerceDashboardProfile(Request $request, $id)
    {
        $commerce_data = $this->get_trackingorderdetails($id);

        $sprintId = $commerce_data['sprintId'];
        $data = $commerce_data['data'];

        return backend_view('walmart-ecomerce.order_profile', compact('data', 'sprintId'));
    }

    public function getWalmartEcommerceDashboardExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }


        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477621,477587];
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('creator_id', $boradlessVendorIds)->whereNotIn('task_status_id', [38, 36])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Tracking #', 'Status'];

        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->delivered_at) {
                $delivered_at = new \DateTime($boradless->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => $boradless->task_status_id ? strval(self::$status[$boradless->task_status_id]) :''
            ];
        }

        Excel::create('Toronto Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Data');
            $excel->sheet('Toronto Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    //Walmart E-Commerce Sorted Function
    public function getWalmartEcommerceSorter(Request $request)
    {
        $title_name = 'Walmart E-commerce';
        $type = 'sorted';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';

        return backend_view('walmart-ecomerce.sorted_order', compact('title_name',  'type','city'));
    }

    public function walmartEcommerceSortedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $city_data = $request->store_name;

        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($city_data == 'ottawa') {
            $ctcVendorIds = [477631,477629];
        } elseif ($city_data == 'vancouver') {
            $ctcVendorIds = [477607,477589,477641];
        } elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477621,477587];
        }
        else
        {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }
        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])
            ->where(['task_status_id' => 133]);
//        $boradlessVendorId = [477542,477559,477518];
//        $ctcVendors = CtcVendor::whereNotIn('vendor_id',[477340,477341,477342,477343,477344,477345,477346])->pluck('vendor_id')->toArray();
//        $boradlessVendorIds = array_merge($boradlessVendorId,$ctcVendors);
//        if (!empty($request->get('store_name'))) {
//            if ($request->get('store_name') == 'borderless_vendors')
//            {
//                $query = $query->whereIn('creator_id', $boradlessVendorId);
//            }
//            elseif ($request->get('store_name') == 'ctc_vendors')
//            {
//                $query = $query->whereIn('creator_id', $ctcVendors);
//            }
//            else
//            {
//                $query = $query->whereIn('creator_id', $boradlessVendorIds);
//            }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else
                {
                    return '';
                }
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {

                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }

            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.action_sorted', compact('record'));
            })
            ->make(true);
    }

    public function getWalmartEcommerceSortedProfile(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('walmart-ecomerce.walmart_ecommerce_sorted_detail', compact('data', 'sprintId'));
    }

    public function walmartEcommerceSortedExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477621,477587];
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->where(['task_status_id' => 133])->whereNotIn('task_status_id', [38, 36])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Tracking #', 'Status'];
        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->delivered_at) {
                $delivered_at = new \DateTime($boradless->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => $boradless->task_status_id ? strval(self::$status[$boradless->task_status_id]):''
            ];

        }
        Excel::create('Toronto Sorted Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Sorted Data');
            $excel->sheet('Toronto Sorted Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    //Walmart E-Commerce Out For Delivery Function
    public function getWalmartEcommercehub(Request $request)
    {
        $title_name = 'Walmart E-commerce';
        $type = 'picked';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('walmart-ecomerce.pickup_hub', compact('title_name',  'type','city'));
    }

    public function walmartEcommercePickedUpData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        /*$boradlessVendorId = [477542,477559,477518];
        $ctcVendors = CtcVendor::whereNotIn('vendor_id',[477340,477341,477342,477343,477344,477345,477346])->pluck('vendor_id')->toArray();
        $boradlessVendorIds = array_merge($boradlessVendorId,$ctcVendors);*/



        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');
        $city_data = $request->store_name;
        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($city_data == 'ottawa') {
            $ctcVendorIds = [477631,477629];
        } elseif ($city_data == 'vancouver') {
            $ctcVendorIds = [477607,477589,477641];
        } elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477621,477587];
        }
        else
        {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])
            ->where(['task_status_id' => 121]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else
                {
                    return '';
                }
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.action_pickup', compact('record'));
            })
            ->make(true);
    }

    public function walmartEcommercepickupDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('walmart-ecomerce.walmart_ecommerce_pickup_detail', compact('data', 'sprintId'));
    }

    public function walmartEcommercePickedupExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477621,477587];
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('creator_id', $boradlessVendorIds)->where(['task_status_id' => 121])->whereNotIn('task_status_id', [38, 36])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Tracking #', 'Status'];
        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->delivered_at) {
                $delivered_at = new \DateTime($boradless->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => $boradless->task_status_id? strval(self::$status[$boradless->task_status_id]): ''
            ];
        }
        Excel::create('Toronto Picked Up Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Picked Up Data');
            $excel->sheet('Toronto Picked Up Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    //Walmart E-Commerce Out For Delivery Function
    public function getWalmartEcommercescan(Request $request)
    {
        $title_name = 'Walmart E-commerce';
        $type = 'scan';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('walmart-ecomerce.not_scanned_orders', compact('title_name', 'type','city'));
    }

    public function walmartEcommerceNotScanData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');
        $city_data = $request->store_name;
        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($city_data == 'ottawa') {
            $ctcVendorIds = [477631,477629];
        } elseif ($city_data == 'vancouver') {
            $ctcVendorIds = [477607,477589,477641];
        } elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477621,477587];
        }
        else
        {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [61, 13]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else{
                    return '';
                }
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.action_notscan', compact('record'));
            })
            ->make(true);
    }

    public function walmartEcommercenotscanDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('walmart-ecomerce.walmart_ecommerce_notscan_detail', compact('data', 'sprintId'));
    }

    public function walmartEcommercescanExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477621,477587];
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereIn('task_status_id', [61, 13])->whereNotIn('task_status_id', [38, 36])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Tracking #', 'Status'];
        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->delivered_at) {
                $delivered_at = new \DateTime($boradless->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$boradless->task_status_id])
            ];
        }
        Excel::create('Toronto Not Scan Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Not Scan Data');
            $excel->sheet('Toronto Not Scan Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    //Walmart E-Commerce Delivered Function
    public function getWalmartEcommercedelivered(Request $request)
    {
        $title_name = 'Walmart E-commerce';
        $type = 'delivered';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('walmart-ecomerce.delivered_orders', compact('title_name',  'type','city'));
    }

    public function walmartEcommerceDeliveredData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $city_data = $request->store_name;
        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($city_data == 'ottawa') {
            $ctcVendorIds = [477631,477629];
        } elseif ($city_data == 'vancouver') {
            $ctcVendorIds = [477607,477589,477641];
        } elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477621,477587];
        }
        else
        {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else{
                    return '';
                }
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.action_delivered', compact('record'));
            })
            ->make(true);
    }

    public function walmartEcommercedeliveredDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);

        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('walmart-ecomerce.walmart_delivered_detail', compact('data', 'sprintId'));
    }

    public function walmartEcommerceDeliveredExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477621,477587];
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Tracking #', 'Status'];
        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->delivered_at) {
                $delivered_at = new \DateTime($boradless->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$boradless->task_status_id])
            ];
        }
        Excel::create('Toronto Delivered Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Delivered Data');
            $excel->sheet('Toronto Delivered Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    //Walmart E-Commerce Returned Function
    public function getWalmartEcommercereturned(Request $request)
    {
        $title_name = 'Walmart E-commerce';
        $type = 'return';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('walmart-ecomerce.returned_orders', compact('title_name',  'type','city'));
    }

    public function walmartEcommerceReturnedData(Datatables $datatables, Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');
        $city_data = $request->store_name;
        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        } elseif ($city_data == 'ottawa') {
            $ctcVendorIds = [477631,477629];
        } elseif ($city_data == 'vancouver') {
            $ctcVendorIds = [477607,477589,477641];
        } elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477621,477587];
        }
        else
        {
            $ctcVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }

        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->where('is_custom_route', 0);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('returned_at', static function ($record) {
                if ($record->returned_at) {
                    $returned_at = new \DateTime($record->returned_at, new \DateTimeZone('UTC'));
                    $returned_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $returned_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('hub_return_scan', static function ($record) {
                if ($record->hub_return_scan) {
                    $hub_return_scan = new \DateTime($record->hub_return_scan, new \DateTimeZone('UTC'));
                    $hub_return_scan->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $hub_return_scan->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else{
                    return '';
                }
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.action_returned', compact('record'));
            })
            ->make(true);
    }

    public function walmartEcommercereturnedDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('walmart-ecomerce.walmart_returned_detail', compact('data', 'sprintId'));
    }

    public function walmartEcommerceReturnedExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');
        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        } elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477621,477587];
        }
        else
        {
            $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629,];
        }
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Joey Returned Scan', 'Hub Returned Scan', 'Tracking #', 'Status'];
        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            // $delivered_at = '';
            $returned_at = '';
            $hub_return_scan = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->returned_at) {
                $returned_at = new \DateTime($boradless->returned_at, new \DateTimeZone('UTC'));
                $returned_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $returned_at->format('Y-m-d H:i:s');
            }
            if ($boradless->hub_return_scan) {
                $hub_return_scan = new \DateTime($boradless->hub_return_scan, new \DateTimeZone('UTC'));
                $hub_return_scan->setTimeZone(new \DateTimeZone('America/Toronto'));
                $hub_return_scan->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Joey Returned Scan' => $returned_at,
                'Hub Returned Scan' => $hub_return_scan,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$boradless->task_status_id])
            ];
        }
        Excel::create('Toronto Returned Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Returned Data');
            $excel->sheet('Toronto Returned Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    //Walmart E-Commerce Not Returned Function
    public function getWalmartEcommerceNotreturned(Request $request)
    {
        $title_name = 'Walmart E-commerce';
        $type = 'return';
        return backend_view('walmart-ecomerce.not_returned_orders', compact('title_name',  'type'));
    }

    public function walmartEcommerceNotReturnedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->where('is_custom_route', 0)
            ->whereNull('hub_return_scan');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('picked_up_at', static function ($record) {
                if ($record->picked_up_at) {
                    $picked_up_at = new \DateTime($record->picked_up_at, new \DateTimeZone('UTC'));
                    $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $picked_up_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('sorted_at', static function ($record) {
                if ($record->sorted_at) {
                    $sorted_at = new \DateTime($record->sorted_at, new \DateTimeZone('UTC'));
                    $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $sorted_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('delivered_at', static function ($record) {
                if ($record->delivered_at) {
                    $delivered_at = new \DateTime($record->delivered_at, new \DateTimeZone('UTC'));
                    $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $delivered_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('returned_at', static function ($record) {
                if ($record->returned_at) {
                    $returned_at = new \DateTime($record->returned_at, new \DateTimeZone('UTC'));
                    $returned_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $returned_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('hub_return_scan', static function ($record) {
                if ($record->hub_return_scan) {
                    $hub_return_scan = new \DateTime($record->hub_return_scan, new \DateTimeZone('UTC'));
                    $hub_return_scan->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $hub_return_scan->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joey_name ? $record->joey_name . ' (' . $record->joey_id . ')' : '';
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('task_status_id', static function ($record) {
                if ($record->task_status_id) {
                    return self::$status[$record->task_status_id];
                }
                else{
                    return '';
                }
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                //return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('walmart-ecomerce.action_notreturned', compact('record'));
            })
            ->make(true);
    }

    public function walmartEcommerceNotReturnedDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('walmart-ecomerce.walmart_notreturned_detail', compact('data', 'sprintId'));
    }

    public function walmartEcommerceNotReturnedExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->whereNull('hub_return_scan')->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Joey Returned Scan', 'Hub Returned Scan', 'Tracking #', 'Status'];
        foreach ($boradless_data as $boradless) {
            $picked_up_at = '';
            $sorted_at = '';
            // $delivered_at = '';
            $returned_at = '';
            $hub_return_scan = '';
            if ($boradless->picked_up_at) {
                $picked_up_at = new \DateTime($boradless->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($boradless->sorted_at) {
                $sorted_at = new \DateTime($boradless->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($boradless->returned_at) {
                $returned_at = new \DateTime($boradless->returned_at, new \DateTimeZone('UTC'));
                $returned_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $returned_at->format('Y-m-d H:i:s');
            }
            if ($boradless->hub_return_scan) {
                $hub_return_scan = new \DateTime($boradless->hub_return_scan, new \DateTimeZone('UTC'));
                $hub_return_scan->setTimeZone(new \DateTimeZone('America/Toronto'));
                $hub_return_scan->format('Y-m-d H:i:s');
            }
            $boradless_array[] = [
                'JoeyCo Order #' => strval($boradless->sprint_id),
                'Route Number' => $boradless->route_id ? strval('R-' . $boradless->route_id . '-' . $boradless->ordinal) : '',
                'Joey' => $boradless->joey_name ? strval($boradless->joey_name . ' (' . $boradless->joey_id . ')') : '',
                'Customer Address' => strval($boradless->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Joey Returned Scan' => $returned_at,
                'Hub Returned Scan' => $hub_return_scan,
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$boradless->task_status_id])
            ];
        }
        Excel::create('Toronto Returns Not Received ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Returns Not Received');
            $excel->sheet('Toronto Returns Not Received', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function walmartEcommerceNotReturnedExcelTrackingIds($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('creator_id', $boradlessVendorIds)->whereNotIn('task_status_id', [38, 36])->whereNull('hub_return_scan')->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->get();
        $boradless_array[] = ['Tracking #'];
        foreach ($boradless_data as $boradless) {
            $boradless_array[] = [
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1))
            ];
        }
        Excel::create('Toronto Tracking Not Received' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Toronto Tracking Not Received');
            $excel->sheet('Toronto Tracking Not Received', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Walmart E-Commerce Reporting
     */
    public function getWalmartEcommerceReporting(Request $request)
    {

        $from_date = !empty($request->get('fromdatepicker')) ? $request->get('fromdatepicker') : date("Y-m-d");
        $to_date = !empty($request->get('todatepicker')) ? $request->get('todatepicker') : date("Y-m-d");
        $city = !empty($request->get('city')) ? $request->get('city') : 'all';
        $city = !empty($request->get('creator_id')) ? $request->get('creator_id') : '';
        $interval = date_diff(date_create($from_date), date_create($to_date));

        if ($interval->days > 14) {
            session()->flash('alert-danger', 'The date range selected must be less then or equal to 15 days');
            return redirect('walmart/e-commerce/reporting');
        }
        $all_dates = array();
        $range_from_date = new Carbon($from_date);
        $range_to_date = new Carbon($to_date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();

            $range_from_date->addDay();
        }

        $start_dt = new DateTime($from_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($to_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $sprint_ids = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)
            ->whereNotIn('task_status_id', [38, 36]);
        $city_data = $request->get('creator_id');
        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
        } elseif ($city_data == 'ottawa') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477631,477629]);
        } elseif ($city_data == 'vancouver') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477607,477589,477641]);
        } elseif ($city_data == 'toronto') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477621,477587]);
        }
        else
        {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
        }
        $sprint_ids = $sprint_ids->pluck('id');

        $sprint = new BoradlessDashboard();
        $boradless_count = $sprint->getSprintCounts($sprint_ids);

        foreach ($all_dates as $range_date) {

            $start_dt = new DateTime($range_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($range_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $sprint_ids = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)
                ->whereNotIn('task_status_id', [38, 36]);

            if ($city_data == 'all') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
            } elseif ($city_data == 'ottawa') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477631,477629]);
            } elseif ($city_data == 'vancouver') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477607,477589,477641]);
            } elseif ($city_data == 'toronto') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477621,477587]);
            }
            else
            {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
            }

            $sprint_ids = $sprint_ids->pluck('id');
            $sprint = new BoradlessDashboard();
            $boradless_range_count[$range_date] = $sprint->getSprintCounts($sprint_ids);
        }


        return backend_view('walmart-ecomerce.reporting.walmart_reporting', compact('boradless_count',
            'boradless_range_count',
            'city'
        ));
    }

    /**
     * Get Walmart E-Commerce Route Info
     */
    public function getWalmartEcommerceReportingData(Datatables $datatables, Request $request)
    {
        if ($request->ajax()) {

            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $data_for = $request->data_for;
            $storeName = $request->vendor_data;

            $start_dt = new DateTime($from_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($from_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)
                ->whereNotIn('task_status_id', [38, 36]);

            if ($storeName == 'all') {
                $query = $query->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
            } elseif ($storeName == 'ottawa') {
                $query = $query->whereIn('creator_id', [477631,477629]);
            } elseif ($storeName == 'vancouver') {
                $query = $query->whereIn('creator_id', [477607,477589,477641]);
            } elseif ($storeName == 'toronto') {
                $query = $query->whereIn('creator_id', [477621,477587]);
            }
            else
            {
                $query = $query->whereIn('creator_id', [477621, 477587, 477607, 477589, 477641, 477631, 477629]);
            }




            //$query = Sprint::whereIn('id', $sprintids)->where('deleted_at', null)->where('is_reattempt','=', 0);

            //$query = DB::table('boradless_dashboard')->whereBetween(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), [$from_date, $to_date]);
            $sprint = new BoradlessDashboard();
            // useing fillters
            ($data_for == 'picked-up') ? $query->where('task_status_id', 125) : $query;
            ($data_for == 'at-hub') ? $query->whereIn('task_status_id', [124, 13]) : $query;
            ($data_for == 'at-store') ? $query->where('task_status_id', 61) : $query;
            ($data_for == 'sorted-order') ? $query->where('task_status_id', 133) : $query;
            ($data_for == 'out-for-delivery') ? $query->where('task_status_id', 121) : $query;
            ($data_for == 'delivered-order') ? $query->whereIn('task_status_id', $sprint->getStatusCodes('competed')) : $query;
            ($data_for == 'returned') ? $query->whereIn('task_status_id', $sprint->getStatusCodes('return'))->where('task_status_id', '!=', 111) : $query;
            ($data_for == 'returned-to-merchant') ? $query->where('task_status_id', 111) : $query;

            // selecting the columns
            /* $query->select([
                 'id',
                 'sprint_status',
                 'created_at',
                 'updated_at',
                 'tracking_id'
             ]);*/

            return $datatables->eloquent($query)
                ->editColumn('status_id', static function ($record) {
                    return self::$status[$record->task_status_id];
                })
                ->addColumn('tracking_id', static function ($record) {
                    if ($record->tracking_id) {
                        if (str_contains($record->tracking_id, 'old_')) {
                            return substr($record->tracking_id, strrpos($record->tracking_id, '_') + 0);
                        }
                        else
                        {
                            return $record->tracking_id;
                        }
                    } else {
                        "";
                    }
                })
                ->addColumn('store_name', static function ($record) {
                    if ($record->store_name) {
                        return $record->store_name;
                    } else {
                        "";
                    }
                })
                ->addColumn('address', static function ($record) {
                    if(isset($record->address_line_1))
                    {
                        return $record->address_line_1;
                    }
                    elseif (isset($record->address_line_2))
                    {
                        return $record->address_line_2;
                    }
                    else
                    {
                        return $record->address_line_3 ? $record->address_line_3 : '';
                    }
                })
                ->addColumn('action', static function ($record) {
                    return backend_view('walmart-ecomerce.action', compact('record'));
                })
                ->editColumn('created_at', static function ($record) {
                    return (new \DateTime($record->created_at))->setTimezone(new \DateTimeZone('America/Toronto'))->format('Y-m-d H:i:s');
                })
                ->editColumn('updated_at', static function ($record) {
                    return (new \DateTime($record->updated_at))->setTimezone(new \DateTimeZone('America/Toronto'))->format('Y-m-d H:i:s');
                })
                ->make(true);

        }
    }

    public function getWalmartEcommerceCards(Request $request)
    {
        $type = 'all';
        return backend_view('walmart-ecomerce.walmart_card_dashboard', compact( 'type'));
    }

    public function walmartEcommerceTotalCards($date, $type, $vendor_id = null)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $allVendors = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        if ($vendor_id == 'all-vendors')
        {
            $boradlessVendorIds = $allVendors;
        }
        elseif ($vendor_id == 'ottawa')
        {
            $boradlessVendorIds = [477631,477629];
        }
        elseif ($vendor_id == 'toronto')
        {
            $boradlessVendorIds = [477621,477587];
        }
        elseif ($vendor_id == 'vancouver')
        {
            $boradlessVendorIds = [477607,477589,477641];
        }

        else
        {
            $boradlessVendorIds = $allVendors;
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $taskIds = DB::table('boradless_dashboard')->whereIn('creator_id', $boradlessVendorIds)->where('created_at','>',$start)->where('created_at','<',$end)
            ->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->whereNull('deleted_at')->pluck('task_id');

        $boradless = new BoradlessDashboard();
        $boradless_count = $boradless->getWalmartECommerceCounts($taskIds, $type);
        $response['boradless_count'] = $boradless_count;

        return $response;
    }


    /**
     * Get Walmart E-Commerce OTD Graph
     */
    public function statistics_otd_index(Request $request)
    {
        return backend_view('walmart-ecomerce.otd.statistics_otd_dashboard');
    }

    /**
     * Get Day Walmart E-Commerce OTD Graph
     */
    public function ajax_render_walmart_ecommerce_otd_day(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        $query = BoradlessDashboard::whereIn('creator_id', $boradlessVendorIds)->where('created_at','>',$start)->where('created_at','<',$end)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', $sprint->getStatusCodes('competed'))->get();
        //$query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();

        $totalcount = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $record) {
                $createdTimestamp = strtotime($record->created_at);
                $day = date('D', $createdTimestamp);
                if ($day == 'Sat') {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->boradlessAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+2 day", strtotime($record->boradlessAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                } else {

                    if ($record->deliveryTime()->delivery_time != NULL && $record->boradlessAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+1 day", strtotime($record->boradlessAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                }
                $totalcount++;
            }

            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 0), 'y2' => 100 - round((($totallates / $totalcount) * 100), 0)];
        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
        }

        return response()->json(array('status' => true, 'for' => 'pie_chart1', 'data' => [$odt_data_1]));
    }

    /**
     * Get Week Walmart E-Commerce OTD Graph
     */
    public function ajax_render_walmart_ecommerce_otd_week(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();

        $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];
        $query = BoradlessDashboard::whereIn('creator_id', $boradlessVendorIds)->whereIn('task_status_id', $sprint->getStatusCodes('competed'))->whereNotIn('task_status_id', [38, 36])
            ->whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [date('y-m-d', strtotime('-6 day', strtotime($date))) . ' 20:00:00', $date . " 19:59:59"])->get();

        //$query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();

        $totalcount = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $record) {
                $createdTimestamp = strtotime($record->created_at);
                $day = date('D', $createdTimestamp);
                if ($day == 'Sat') {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->boradlessAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+2 day", strtotime($record->boradlessAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                } else {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->boradlessAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+1 day", strtotime($record->boradlessAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                }
                $totalcount++;
            }
            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 0), 'y2' => 100 - round((($totallates / $totalcount) * 100), 0)];
        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
        }
        return response()->json(array('status' => true, 'for' => 'pie_chart2', 'data' => [$odt_data_1]));
    }

    /**
     * Get Month Walmart E-Commerce OTD Graph
     */
    public function ajax_render_walmart_ecommerce_otd_month(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();
        $boradlessVendorIds = [477621, 477587, 477607, 477589, 477641, 477631, 477629];

        $query = BoradlessDashboard::whereIn('creator_id', $boradlessVendorIds)->whereIn('task_status_id', $sprint->getStatusCodes('competed'))->whereNotIn('task_status_id', [38, 36])
            ->whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), [date('y-m-d', strtotime('-1 month', strtotime($date))) . ' 20:00:00', $date . " 19:59:59"])->get();
        //$query = Sprint::whereIn('id', $sprintIds)->where('deleted_at', null)->where('is_reattempt','=', 0)->get();

        $totalcount = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $record) {
                $createdTimestamp = strtotime($record->created_at);
                $day = date('D', $createdTimestamp);
                if ($day == 'Sat') {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->boradlessAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+2 day", strtotime($record->boradlessAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                } else {
                    if ($record->deliveryTime()->delivery_time != NULL && $record->boradlessAtHubProcessingFirst()->athub && date('d-m-Y', strtotime("+1 day", strtotime($record->boradlessAtHubProcessingFirst()->athub))) . " 21:00:00" < date('d-m-Y H:i:s', strtotime($record->deliveryTime()->delivery_time))) {
                        $totallates++;
                    }
                }
                $totalcount++;
            }
            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 0), 'y2' => 100 - round((($totallates / $totalcount) * 100), 0)];
        } else {
            $odt_data_1 = ['y1' => 100, 'y2' => 0];
        }
        return response()->json(array('status' => true, 'for' => 'pie_chart3', 'data' => [$odt_data_1]));
    }

}