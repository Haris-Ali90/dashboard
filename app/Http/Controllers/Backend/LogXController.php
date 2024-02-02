<?php

namespace App\Http\Controllers\Backend;

use App\BoradlessDashboard;
use App\CTCEntry;
use App\CtcVendor;
use App\CustomerRoutingTrackingId;
use App\JoeyRouteLocations;
use App\MerchantIds;
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

class LogXController extends BackendController
{
    public static $status = array('155' => 'To be re-attempted tomorrow',"136" => "Client requested to cancel the order", "137" => "Delay in delivery due to weather or natural disaster", "118" => "left at back door", "117" => "left with concierge", "135" => "Customer refused delivery", "108" => "Customer unavailable-Incorrect address", "106" => "Customer unavailable - delivery returned", "107" => "Customer unavailable - Left voice mail - order returned", "109" => "Customer unavailable - Incorrect phone number", "142" => "Damaged at hub (before going OFD)", "143" => "Damaged on road - undeliverable", "144" => "Delivery to mailroom", "103" => "Delay at pickup", "139" => "Delivery left on front porch", "138" => "Delivery left in the garage", "114" => "Successful delivery at door", "113" => "Successfully hand delivered", "120" => "Delivery at Hub", "110" => "Returned to hub for re-delivery", "111" => "Delivery to hub for return to merchant", "121" => "Out For Delivery", "102" => "Joey Incident", "104" => "Damaged on road - delivery will be attempted", "105" => "Item damaged - returned to merchant", "129" => "Joey at hub", "128" => "Package on the way to hub", "140" => "Delivery missorted, may cause delay", "116" => "Successful delivery to neighbour", "132" => "Office closed - safe dropped", "101" => "Joey on the way to pickup", "32" => "Order accepted by Joey", "14" => "Merchant accepted", "36" => "Cancelled by JoeyCo", "124" => "At hub - processing", "38" => "Draft", "18" => "Delivery failed", "56" => "Partially delivered", "17" => "Delivery success", "68" => "Joey is at dropoff location", "67" => "Joey is at pickup location", "13" => "At hub - processing", "16" => "Joey failed to pickup order", "57" => "Not all orders were picked up", "15" => "Order is with Joey", "112" => "To be re-attempted", "131" => "Office closed - returned to hub", "125" => "Pickup at store - confirmed", "61" => "Scheduled order", "37" => "Customer cancelled the order", "34" => "Customer is editting the order", "35" => "Merchant cancelled the order", "42" => "Merchant completed the order", "54" => "Merchant declined the order", "33" => "Merchant is editting the order", "29" => "Merchant is unavailable", "24" => "Looking for a Joey", "23" => "Waiting for merchant(s) to accept", "28" => "Order is with Joey", "133" => "Packages sorted", "55" => "ONLINE PAYMENT EXPIRED", "12" => "ONLINE PAYMENT FAILED", "53" => "Waiting for customer to pay", "141" => "Lost package", "60" => "Task failure", '153' => 'Miss sorted to be reattempt', '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow');
	
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
            "121" => "Out For Delivery",
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

    //Logx Function
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

    //Logx Function
    public function getLogX(Request $request)
    {

        $city = !empty($request->get('city')) ? $request->get('city') : 'all';
        $status_code = array_intersect_key(self::$status, [61 => '', 124 => '', 121 => '', 133 => '', 17 => '', 113 => '', 114 => '', 116 => '', 117 => '', 118 => '', 132 => '', 138 => '', 139 => '', 144 => '', 104 => '', 105 => '', 106 => '', 107 => '', 108 => '', 109 => '', 110 => '', 111 => '', 112 => '', 131 => '', 135 => '', 136 => '']);
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        return backend_view('logxDashboard.logx_dashboard', compact('city', 'status_code'));
    }

    public function logXData(Datatables $datatables, Request $request)
    {


        $sprintId = 0;
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $city_data = $request->city;

        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
        }

        $start_dt = new DateTime($today_date . " 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date . " 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        if (!empty($request->get('tracking_id'))) {
            $task_id = MerchantIds::where('tracking_id', $request->get('tracking_id'))->whereNull('deleted_at')->first();
            if ($task_id) {
                $sprint = Task::where('id', $task_id->task_id)->first();
                $sprintId = $sprint->sprint_id;
            }
        }
        //dd($sprintId);
        if (!empty($request->get('route_id'))) {
            $task_ids = JoeyRouteLocations::where('route_id', $request->get('route_id'))->whereNull('deleted_at')->pluck('task_id');

            if ($task_ids) {
                $sprintIds = Task::whereIn('id', $task_ids)->pluck('sprint_id');
            }
        }
        if (!empty($request->get('tracking_id'))) {
            $query = BoradlessDashboard::where('tracking_id', $request->get('tracking_id'))->where('sprint_id', $sprintId)->whereNull('deleted_at');
        } else if (!empty($request->get('route_id'))) {
            $query = BoradlessDashboard::where('route_id', $request->get('route_id'))->whereIn('creator_id', $ctcVendorIds)->whereIn('sprint_id', $sprintIds)->whereNull('deleted_at');
        } else {
            // $ctcVendorIds = CtcVendor::pluck('vendor_id');
            $query = BoradlessDashboard::whereIn('creator_id', $ctcVendorIds)->where('created_at', '>', $start)->where('created_at', '<', $end)->whereNotIn('task_status_id', [38, 36]);
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
            return backend_view('logxDashboard.logx-action', compact('record'));
        })->make(true);

    }

    public function LogXProfile(Request $request, $id)
    {
        $commerce_data = $this->get_trackingorderdetails($id);

        $sprintId = $commerce_data['sprintId'];
        $data = $commerce_data['data'];
        return backend_view('logxDashboard.logx_profile', compact('data', 'sprintId'));
    }

    public function getLogXDashboardExcel($date = null,$vendor = null)
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
            $boradlessVendorIds = [477661];
        }  elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
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

    public function logxTotalCards($date, $type)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $logx = [477661];

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $taskIds = \Illuminate\Support\Facades\DB::table('boradless_dashboard')->whereIn('creator_id', $logx)->where('created_at','>',$start)->where('created_at','<',$end)
            ->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->whereNull('deleted_at')->pluck('task_id');

        $boradless = new BoradlessDashboard();
        $boradless_count = $boradless->getLogxECommerceCounts($taskIds, $type);
        $response['boradless_count'] = $boradless_count;
        return $response;
    }
    public function getLogxCards(Request $request)
    {
        $type = 'all';
        return backend_view('logxDashboard.logx_card_dashboard', compact( 'type'));
    }
    public function getLogxEcommerceCards(Request $request)
    {
        $type = 'all';
        return backend_view('logxDashboard.logx_card_dashboard', compact( 'type'));
    }
    /*Logx Dashboard Cards*/

    public function getLogxCustomRouteData($date)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $logx = [477661];

        $custom_route = \Illuminate\Support\Facades\DB::table('boradless_dashboard')->whereIn('creator_id', $logx)->where('created_at','>',$start)->where('created_at','<',$end)->whereNull('deleted_at')->where('is_custom_route', 1)->count();
        $response['custom_route'] = $custom_route;
        return $response;
    }
    public function logxInProgressOrders($date, $type)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $logx = [477661];

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $taskIds = \Illuminate\Support\Facades\DB::table('boradless_dashboard')->whereIn('creator_id', $logx)->where('created_at','>',$start)->where('created_at','<',$end)
            ->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->whereNull('deleted_at')->pluck('task_id');

        $boradless = new BoradlessDashboard();
        $boradless_count = $boradless->getInprogressOrders($taskIds, $type);
        $response['newyork_inprogess_count'] = $boradless_count;
        return $response;
    }
    public function logxEcommerceTotalCards($date, $type, $vendor_id = null)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $allVendors = [477661];
        if ($vendor_id == 'all-vendors')
        {
            $boradlessVendorIds = $allVendors;
        }

        elseif ($vendor_id == 'toronto')
        {
            $boradlessVendorIds = [477661];
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
        $boradless_count = $boradless->getLogxECommerceCounts($taskIds, $type);
        $response['boradless_count'] = $boradless_count;

        return $response;
    }

    public function getLogxEcommerceDashboard(Request $request)
    {
        $type = 'total';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('logxDashboard.logx_order_dashboard', compact( 'type','city'));
    }
    //Logx E-Commerce Dashboard Data
    public function getLogxEcommerceDashboardData(Datatables $datatables, Request $request)
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
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
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
                return backend_view('logxDashboard.order_action', compact('record'));
            })
            ->make(true);
    }
    public function getLogxExcel($date = null)
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


        $boradlessVendorIds = 477661;
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->where('creator_id', $boradlessVendorIds)->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')->get();
        $boradless_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'tracking #', 'Status'];

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
                'tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => $boradless->task_status_id ? strval(self::$status[$boradless->task_status_id]) :''
            ];
        }

        Excel::create('Logx Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Data');
            $excel->sheet('Logx Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommerceDashboardExcel($date = null,$vendor = null)
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
            $boradlessVendorIds = [477661];
        } /*elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        }*/ elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
        }

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('creator_id', $boradlessVendorIds)->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')->get();
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

        Excel::create('Logx Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Data');
            $excel->sheet('Logx Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommerceSorter(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'sorted';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';

        return backend_view('logxDashboard.sorted_order', compact('title_name',  'type','city'));
    }

    public function logxEcommerceSortedData(Datatables $datatables, Request $request)
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
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
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
                return backend_view('logxDashboard.action_sorted', compact('record'));
            })
            ->make(true);
    }
    public function getLogxEcommerceReceivedAtHub(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'received-at-hub';
        $status = $request->get('status');

        return backend_view('logxDashboard.received-at-hub', compact('title_name',  'type','status'));
    }

    public function logxEcommerceReceivedAtHubData(Datatables $datatables, Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');

        $start_dt = new DateTime($startDate." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($endDate." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $statusId=[];

        $trackingIds = CustomerRoutingTrackingId::where('vendor_id',477661)->where('valid_id',1)->where('created_at','>',$start)->where('created_at','<',$end)
            ->where('is_inbound', 1)->pluck('tracking_id');

        $query = BoradlessDashboard::whereIn('tracking_id', $trackingIds);

//        if(isset($status)){
//            if($status == 'delivered'){
//                $statusId = [113, 114, 116, 117, 118, 132, 138, 139, 144];
//                $query = $query->whereIn('task_status_id', $statusId);
//            }
//            if($status == 'return'){
//                $statusId = [104, 105, 106, 107, 108, 109, 110, 111, 131, 135, 143, 146];
//                $query = $query->whereIn('task_status_id', $statusId);
//            }
//        }
        $query = $query->groupBy('tracking_id');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
            })
            ->editColumn('tracking_id', static function ($record) {
                if (str_contains($record->tracking_id, 'old_')) {
                    return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
                }
                else
                {
                    return $record->tracking_id;
                }
            })
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('current_status', static function ($record) {
                return self::$status[$record->task_status_id];
            })
            ->editColumn('date', static function ($record) {
                $receivedAthubDate = CustomerRoutingTrackingId::where('tracking_id',$record->tracking_id)->where('is_inbound', 1)->first();
                if ($receivedAthubDate->created_at) {
                    $date = new \DateTime($receivedAthubDate->created_at, new \DateTimeZone('UTC'));
                    $date->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $date->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->addColumn('action', static function ($record) {
                return backend_view('logxDashboard.action_sorted', compact('record'));
            })
            ->make(true);
    }

    public function getLogxEcommerceHubReturnScan(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'hub-return-scan';
        $status = $request->get('status');

        return backend_view('logxDashboard.hub-return-scan', compact('title_name',  'type','status'));
    }

    public function logxEcommerceHubReturnScanData(Datatables $datatables, Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');

        $start_dt = new DateTime($startDate." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($endDate." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $sprintIds = SprintReattempt::where('created_at','>',$start)->where('created_at','<',$end)->pluck('sprint_id');
        $statusId=[];

        $query = BoradlessDashboard::where('creator_id', 477661)->whereIn('sprint_id', $sprintIds);

//        if(isset($status)){
//            if($status == 'delivered'){
//                $statusId = [113, 114, 116, 117, 118, 132, 138, 139, 144];
//                $query = $query->whereIn('task_status_id', $statusId);
//            }
//            if($status == 'return'){
//                $statusId = [104, 105, 106, 107, 108, 109, 110, 111, 131, 135, 143, 146];
//                $query = $query->whereIn('task_status_id', $statusId);
//            }
//        }

        $query = $query->groupBy('tracking_id');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_id', static function ($record) {
                return $record->sprint_id ;
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
            ->editColumn('route_id', static function ($record) {
                return $record->route_id ? 'R-' . $record->route_id . '-' . $record->ordinal : '';
            })
            ->editColumn('current_status', static function ($record) {
                return self::$status[$record->task_status_id];
            })
            ->editColumn('date', static function ($record) {
                $reattemptDate = SprintReattempt::where('sprint_id',$record->sprint_id)->first();
                if ($reattemptDate->created_at) {
                    $date = new \DateTime($reattemptDate->created_at, new \DateTimeZone('UTC'));
                    $date->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $date->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })

            ->addColumn('action', static function ($record) {
                return backend_view('logxDashboard.action_sorted', compact('record'));
            })
            ->make(true);
    }

    public function logxEcommerceSortedExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477661];
        } /*elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        }*/ elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->where(['task_status_id' => 133])->orderBy('sprint_id','desc')->whereNotIn('task_status_id', [38, 36])->get();
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
        Excel::create('Logx Sorted Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Sorted Data');
            $excel->sheet('Logx Sorted Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
/*Profile Details*/
    public function getLogxEcommerceDashboardProfile(Request $request, $id)
    {
        $commerce_data = $this->get_trackingorderdetails($id);

        $sprintId = $commerce_data['sprintId'];
        $data = $commerce_data['data'];

        return backend_view('logxDashboard.order_profile', compact('data', 'sprintId'));
    }
    public function getLogxEcommerceSortedProfile(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('logxDashboard.logx_ecommerce_sorted_detail', compact('data', 'sprintId'));
    }
    public function logxEcommercepickupDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('logxDashboard.logx_ecommerce_pickup_detail', compact('data', 'sprintId'));
    }
    public function logxEcommercenotscanDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('logxDashboard.logx_ecommerce_notscan_detail', compact('data', 'sprintId'));
    }
    public function logxEcommercedeliveredDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);

        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('logxDashboard.logx_delivered_detail', compact('data', 'sprintId'));
    }
    public function logxEcommercereturnedDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('logxDashboard.logx_returned_detail', compact('data', 'sprintId'));
    }
    public function logxEcommerceNotReturnedDetail(Request $request, $id)
    {
        $boradless_id = base64_decode($id);
        $data = $this->get_trackingorderdetails($boradless_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('logxDashboard.logx_notreturned_detail', compact('data', 'sprintId'));
    }
    public function getLogxEcommercehub(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'picked';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('logxDashboard.pickup_hub', compact('title_name',  'type','city'));
    }
    public function logxEcommercePickedUpData(Datatables $datatables, Request $request)
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
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
        }

        $query = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $ctcVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])
            ->where('task_status_id',121);

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
                return backend_view('logxDashboard.action_pickup', compact('record'));
            })
            ->make(true);
    }
    public function logxEcommercePickedupExcel($date = null,$vendor = null)
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
            $boradlessVendorIds = [477661];
        } /*elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        }*/ elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
        }

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('creator_id', $boradlessVendorIds)->where(['task_status_id' => 121])->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')->get();
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
        Excel::create('Logx Picked Up Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Picked Up Data');
            $excel->sheet('Logx Picked Up Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommercescan(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'scan';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('logxDashboard.not_scanned_orders', compact('title_name', 'type','city'));
    }
    public function logxEcommerceNotScanData(Datatables $datatables, Request $request)
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
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
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
                return backend_view('logxDashboard.action_notscan', compact('record'));
            })
            ->make(true);
    }
    public function logxEcommercescanExcel($date = null,$vendor = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $boradlessVendorIds = [];
        if ($vendor == 'all') {
            $boradlessVendorIds = [477661];
        } /*elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        } */elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
        }

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereIn('task_status_id', [61, 13])->orderBy('sprint_id','desc')->whereNotIn('task_status_id', [38, 36])->get();
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
        Excel::create('Logx Not Scan Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Not Scan Data');
            $excel->sheet('Logx Not Scan Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommercedelivered(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'delivered';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('logxDashboard.delivered_orders', compact('title_name',  'type','city'));
    }
    public function logxEcommerceDeliveredData(Datatables $datatables, Request $request)
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
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
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
                return backend_view('logxDashboard.action_delivered', compact('record'));
            })
            ->make(true);
    }
    public function logxEcommerceDeliveredExcel($date = null,$vendor = null)
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
            $boradlessVendorIds = [477661];
        } /*elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        }*/ elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
        }

        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')
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
        Excel::create('Logx Delivered Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Delivered Data');
            $excel->sheet('Logx Delivered Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommercereturned(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'return';
        $city = !empty($request->get('store_name')) ? $request->get('store_name') : 'all';
        return backend_view('logxDashboard.returned_orders', compact('title_name',  'type','city'));
    }
    public function statistics_otd_index(Request $request)
    {
        return backend_view('logxDashboard.otd.statistics_otd_dashboard');
    }



    public function logxEcommerceReturnedData(Datatables $datatables, Request $request)
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
            $ctcVendorIds = [477661];
        }  elseif ($city_data == 'toronto') {
            $ctcVendorIds = [477661];
        }
        else
        {
            $ctcVendorIds = [477661];
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
                return backend_view('logxDashboard.action_returned', compact('record'));
            })
            ->make(true);
    }
    public function logxEcommerceReturnedExcel($date = null,$vendor = null)
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
            $boradlessVendorIds = [477661];
        } /*elseif ($vendor == 'ottawa') {
            $boradlessVendorIds = [477631,477629];
        } elseif ($vendor == 'vancouver') {
            $boradlessVendorIds = [477607,477589,477641];
        }*/ elseif ($vendor == 'toronto') {
            $boradlessVendorIds = [477661];
        }
        else
        {
            $boradlessVendorIds = [477661];
        }
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36])->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->orderBy('sprint_id','desc')->get();
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
        Excel::create('Logx Returned Data ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Returned Data');
            $excel->sheet('Logx Returned Data', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommerceNotreturned(Request $request)
    {
        $title_name = 'Logx E-commerce';
        $type = 'return';
        return backend_view('logxDashboard.not_returned_orders', compact('title_name',  'type'));
    }
    public function logxEcommerceNotReturnedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $boradlessVendorIds = [477661];


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
                return backend_view('logxDashboard.action_notreturned', compact('record'));
            })
            ->make(true);
    }
    public function logxEcommerceNotReturnedExcel($date = null)
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

        $boradlessVendorIds = [477661];
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->whereIn('creator_id', $boradlessVendorIds)->where('is_custom_route', 0)->orderBy('sprint_id','desc')->whereNotIn('task_status_id', [38, 36])->whereNull('hub_return_scan')->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->get();
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
        Excel::create('Logx Returns Not Received ' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Returns Not Received');
            $excel->sheet('Logx Returns Not Received', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function logxEcommerceNotReturnedExcelTrackingIds($date = null)
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

        $boradlessVendorIds = [477661];
        $boradless_data = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('creator_id', $boradlessVendorIds)->orderBy('sprint_id','desc')->whereNotIn('task_status_id', [38, 36])->whereNull('hub_return_scan')->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->get();
        $boradless_array[] = ['Tracking #'];
        foreach ($boradless_data as $boradless) {
            $boradless_array[] = [
                'Tracking #' => strval(substr($boradless->tracking_id, ($pos = strrpos($boradless->tracking_id, '_')) == false ? 0 : $pos + 1))
            ];
        }
        Excel::create('Logx Tracking Not Received' . $date . '', function ($excel) use ($boradless_array) {
            $excel->setTitle('Logx Tracking Not Received');
            $excel->sheet('Logx Tracking Not Received', function ($sheet) use ($boradless_array) {
                $sheet->fromArray($boradless_array, null, 'A1', false, false);
            });
        })->download('csv');
    }
    public function getLogxEcommerceReporting(Request $request)
    {

        $from_date = !empty($request->get('fromdatepicker')) ? $request->get('fromdatepicker') : date("Y-m-d");
        $to_date = !empty($request->get('todatepicker')) ? $request->get('todatepicker') : date("Y-m-d");
        $city = !empty($request->get('city')) ? $request->get('city') : 'all';
        $city = !empty($request->get('creator_id')) ? $request->get('creator_id') : '';
        $interval = date_diff(date_create($from_date), date_create($to_date));

        if ($interval->days > 14) {
            session()->flash('alert-danger', 'The date range selected must be less than or equal to 15 days');
            return redirect('logx/e-commerce/reporting');
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

        $sprint_ids = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->orderBy('sprint_id','desc')
            ->whereNotIn('task_status_id', [38, 36]);
        $city_data = $request->get('creator_id');
        $ctcVendorIds = [];
        if ($city_data == 'all') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477661]);
        } /*elseif ($city_data == 'ottawa') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477631,477629]);
        } elseif ($city_data == 'vancouver') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477607,477589,477641]);
        }*/ elseif ($city_data == 'toronto') {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477661]);
        }
        else
        {
            $sprint_ids = $sprint_ids->whereIn('creator_id', [477661]);
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

            $sprint_ids = BoradlessDashboard::where('created_at','>',$start)->where('created_at','<',$end)->orderBy('sprint_id','desc')
                ->whereNotIn('task_status_id', [38, 36]);

            if ($city_data == 'all') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477661]);
            } /*elseif ($city_data == 'ottawa') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477631,477629]);
            } elseif ($city_data == 'vancouver') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477607,477589,477641]);
            }*/ elseif ($city_data == 'toronto') {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477661]);
            }
            else
            {
                $sprint_ids = $sprint_ids->whereIn('creator_id', [477661]);
            }

            $sprint_ids = $sprint_ids->pluck('id');
            $sprint = new BoradlessDashboard();
            $boradless_range_count[$range_date] = $sprint->getSprintCounts($sprint_ids);
        }


        return backend_view('logxDashboard.reporting.logx_reporting', compact('boradless_count',
            'boradless_range_count',
            'city'
        ));
    }
    public function getLogxEcommerceReportingData(Datatables $datatables, Request $request)
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
                $query = $query->whereIn('creator_id', [477661]);
            }  elseif ($storeName == 'toronto') {
                $query = $query->whereIn('creator_id', [477661]);
            }
            else
            {
                $query = $query->whereIn('creator_id', [477661]);
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
                    return backend_view('logxDashboard.action', compact('record'));
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
    /**
     * Get Day Logx OTD Graph
     */
    public function ajax_render_boradless_otd_day(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $boradlessVendorIds = 477661;
        $query = BoradlessDashboard::where('creator_id', $boradlessVendorIds)->where('created_at','>',$start)->where('created_at','<',$end)->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')
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
     * Get Week Logx OTD Graph
     */
    public function ajax_render_boradless_otd_week(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();

        $boradlessVendorIds = 477661;
        $query = BoradlessDashboard::where('creator_id', $boradlessVendorIds)->whereIn('task_status_id', $sprint->getStatusCodes('competed'))->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')
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
     * Get Month Logx OTD Graph
     */
    public function ajax_render_boradless_otd_month(Request $request)
    {
        $date = date("Y-m-d");
        $sprint = new Sprint();
        $boradlessVendorIds = 477661;

        $query = BoradlessDashboard::where('creator_id', $boradlessVendorIds)->whereIn('task_status_id', $sprint->getStatusCodes('competed'))->whereNotIn('task_status_id', [38, 36])->orderBy('sprint_id','desc')
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
    public function getWildforkEcommerceDashboardProfile(Request $request, $id)
    {
        $commerce_data = $this->get_trackingorderdetails($id);

        $sprintId = $commerce_data['sprintId'];
        $data = $commerce_data['data'];


        return backend_view('logxDashboard.order_profile', compact('data', 'sprintId'));
    }



}