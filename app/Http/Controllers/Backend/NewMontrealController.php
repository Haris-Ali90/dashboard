<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Fcm;
use App\CustomerFlagCategories;
use App\FlagHistory;
use App\TaskHistory;
use App\AmazonEnteries;
use App\JoeyRouteLocations;
use App\Sprint;
use App\SprintReattempt;
use App\SprintTaskHistory;
use App\TrackingNote;
use App\UserDevice;
use App\UserNotification;
use Illuminate\Http\Request;
use App\Amazon;
use App\Amazon_count;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\JoeyRoutes;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use DateTime;
use DateTimeZone;
class NewMontrealController extends BackendController
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
        "145" => 'Returned To Merchant',
        "146" => "Delivery Missorted, Incorrect Address",
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow');

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
            "145" => 'Returned To Merchant',
            "146" => "Delivery Missorted, Incorrect Address",
            '153' => 'Miss sorted to be reattempt',
            '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
            '155' => 'To be re-attempted tomorrow');
        return $statusid[$id];
    }

    public function getRoutes($date, $type)
    {
        $date = !empty($date) ? $date : date("Y-m-d");
        $date = date('Y-m-d', strtotime($date . ' -1 days'));
        if ($type == 'all') {
            $routes = '';
        }
        elseif ($type == 'total') {
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        } elseif ($type == 'sorted') {
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['task_status_id' => 133, 'creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        } elseif ($type == 'picked') {
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['task_status_id' => 121, 'creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        } elseif ($type == 'delivered') {
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->whereIn('task_status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        } elseif ($type == 'return') {
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        } elseif ($type == 'scan') {
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->whereIn('task_status_id', [61, 13])->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        } else {
            $date = date('Y-m-d', strtotime($date . ' +1 days'));
            $routes = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['creator_id' => 477260])->where('is_custom_route', 1)->whereNotNull('route_id')->groupBy('route_id')->select('id', 'route_id')->get();
        }
        return $routes;
    }

    public function getJoeys($date, $type)
    {
        $date = !empty($date) ? $date : date("Y-m-d");
        $date = date('Y-m-d', strtotime($date . ' -1 days'));
        if ($type == 'all') {
            $joeys = '';
        }
            elseif ($type == 'total') {
                $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        } elseif ($type == 'sorted') {
            $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['task_status_id' => 133, 'creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        } elseif ($type == 'picked') {
            $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['task_status_id' => 121, 'creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        } elseif ($type == 'delivered') {
            $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->whereIn('task_status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        } elseif ($type == 'return') {
            $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        } elseif ($type == 'scan') {
            $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->whereIn('task_status_id', [61, 13])->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        } else {
            $date = date('Y-m-d', strtotime($date . ' +1 days'));
            $joeys = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->where(['creator_id' => 477260])->where('is_custom_route', 1)->whereNotNull('joey_id')->orderBy('joey_name', 'ASC')->groupBy('joey_id')->select('id', 'joey_id', 'joey_name')->get();
        }
        return $joeys;
    }

    public function montrealTotalCards($date, $type)
    {

        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $taskIds = DB::table('amazon_enteries')
            ->where(['creator_id' => 477260])
            ->where('is_custom_route', 0)
            ->where('created_at','>',$start)->where('created_at','<',$end)
            ->pluck('task_id');

        $amazon = new AmazonEnteries();
        $amazon_count = $amazon->getAmazonCountsForLoop($taskIds, $type);
        $response['amazon_count'] = $amazon_count;
        return $response;
    }

    public function montrealInProgressOrders($date, $type)
    {

        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $taskIds = DB::table('amazon_enteries')
            ->where(['creator_id' => 477260])
            ->where('is_custom_route', 0)
            ->where('created_at','>',$start)->where('created_at','<',$end)
            ->pluck('task_id');

        $amazon = new AmazonEnteries();
        $amazon_count = $amazon->getInprogressOrders($taskIds, $type);
        $response['amazon_inprogress_count'] = $amazon_count;
        return $response;
    }

    public function montrealTotalCardsForLoop($date, $type)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $taskIds = DB::table('amazon_enteries')
            ->where(['creator_id' => 477260])
            ->where('is_custom_route', 0)
            ->where('created_at','>',$start)->where('created_at','<',$end)
            ->pluck('task_id');

        $amazon = new AmazonEnteries();
        $amazon_count = $amazon->getAmazonCountsForLoop($taskIds, $type);
        $response['amazon_count'] = $amazon_count;
        return $response;
    }

    public function getMainfestOrderData($date)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

       /* $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $mainfest_orders = DB::table('mainfest_fields')->where(['vendor_id' => 477260])->where('created_at','>',$start)->where('created_at','<',$end)->pluck('id');*/
        $response['mainfest_orders'] = 0;//count($mainfest_orders);
        return $response;
    }

    public function getFailedOrderData($date)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $failed_orders = DB::table('xml_failed_orders')->join('mainfest_fields', 'mainfest_fields.trackingID', '=', 'xml_failed_orders.tracking_id')
            ->where('xml_failed_orders.created_at','>',$start)->where('xml_failed_orders.created_at','<',$end)->whereNotNull('mainfest_fields.trackingID')
            ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477260])->count();
        $response['failed_orders'] = $failed_orders;
        return $response;
    }

    public function getYesterdayOrderData($date)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $yesterday_return_orders = DB::table('amazon_enteries')->join('sprint_reattempts', 'amazon_enteries.sprint_id', '=', 'sprint_reattempts.sprint_id')
            ->where('amazon_enteries.created_at','>',$start)->where('amazon_enteries.created_at','<',$end)
            ->where(['amazon_enteries.creator_id' => 477260])->count();
        $response['yesterday_return_orders'] = $yesterday_return_orders;
        return $response;
    }

    public function getCustomRouteData($date)
    {
        $response = [];
        $date = !empty($date) ? $date : date("Y-m-d");

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $custom_route = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
            ->where(['creator_id' => 477260])->where('is_custom_route', 1)->count();
        $response['custom_route'] = $custom_route;
        return $response;
    }

    public function getMontrealCards(Request $request)
    {
        $type = 'all';
        return backend_view('newmontrealdashboard.montreal_card_dashboard', compact( 'type'));
    }

    public function getMontreal(Request $request)
    {
        //$route_id = $request->get('route_id');
       // $joey_id = $request->get('joey_id');
        $type = 'total';
        return backend_view('newmontrealdashboard.montreal_dashboard', compact( 'type'));
    }

    public function montrealData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)
            ->where('creator_id' ,477260)->where('is_custom_route', 0);
      /*  if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action', compact('record'));
            })
            ->make(true);
    }

    public function montrealProfile(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('newmontrealdashboard.montreal_profile', compact('data', 'sprintId'));
    }

    public function montrealExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->where(['creator_id' => 477260])->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status'];

        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($montreal->delivered_at) {
                $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Data');
            $excel->sheet('Montreal Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getSorter(Request $request)
    {
       // $route_id = $request->get('route_id');
       // $joey_id = $request->get('joey_id');

        $title_name = 'Montreal';
        $type = 'sorted';
        return backend_view('newmontrealdashboard.sorted_order', compact('title_name',  'type'));
    }

    public function montrealSortedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)
            ->where(['task_status_id' => 133, 'creator_id' => 477260]);
      /*  if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {

                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);

            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_sorted', compact('record'));
            })
            ->make(true);
    }

    public function montrealsortedDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_sorted_detail', compact('data', 'sprintId'));
    }

    public function montrealSortedExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->where(['task_status_id' => 133, 'creator_id' => 477260])->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($montreal->delivered_at) {
                $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];

        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Sorted Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Sorted Data');
            $excel->sheet('Montreal Sorted Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getMontrealhub(Request $request)
    {
       // $route_id = $request->get('route_id');
       // $joey_id = $request->get('joey_id');
        $title_name = 'Montreal';
        $type = 'picked';
        return backend_view('newmontrealdashboard.pickup_hub', compact('title_name',  'type'));
    }

    public function montrealPickedUpData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)
            ->where(['task_status_id' => 121, 'creator_id' => 477260]);
      /*  if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_pickup', compact('record'));
            })
            ->make(true);
    }

    public function montrealpickupDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_pickup_detail', compact('data', 'sprintId'));
    }

    public function montrealPickedupExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->where(['task_status_id' => 121, 'creator_id' => 477260])->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($montreal->delivered_at) {
                $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Picked Up Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Picked Up Data');
            $excel->sheet('Montreal Picked Up Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getMontnotscan(Request $request)
    {
       // $route_id = $request->get('route_id');
       // $joey_id = $request->get('joey_id');
        $title_name = 'Montreal';
        $type = 'scan';
        return backend_view('newmontrealdashboard.not_scanned_orders', compact('title_name', 'type'));
    }

    public function montrealNotScanData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)
            ->whereIn('task_status_id', [61, 13])
            ->where('creator_id' ,477260);
      /*  if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_notscan', compact('record'));
            })
            ->make(true);
    }

    public function montrealnotscanDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_notscan_detail', compact('data', 'sprintId'));
    }

    public function montrealNotscanExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('task_status_id', [61, 13])
            ->where('creator_id' , 477260)->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($montreal->delivered_at) {
                $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Not Scan Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Not Scan Data');
            $excel->sheet('Montreal Not Scan Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getMontdelivered(Request $request)
    {
        //$route_id = $request->get('route_id');
        //$joey_id = $request->get('joey_id');
        $title_name = 'Montreal';
        $type = 'delivered';
        return backend_view('newmontrealdashboard.delivered_orders', compact('title_name',  'type'));
    }

    public function montrealDeliveredData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)
            ->whereIn('task_status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->where('creator_id', 477260);
        /*if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_delivered', compact('record'));
            })
            ->make(true);
    }

    public function montrealdeliveredDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_delivered_detail', compact('data', 'sprintId'));
    }

    public function montrealDeliveredExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)
            ->whereIn('task_status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->where('creator_id', 477260)->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($montreal->delivered_at) {
                $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Delivered Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Delivered Data');
            $excel->sheet('Montreal Delivered Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getMontreturned(Request $request)
    {
        //$route_id = $request->get('route_id');
       // $joey_id = $request->get('joey_id');
        $title_name = 'Montreal';
        $type = 'return';
        return backend_view('newmontrealdashboard.returned_orders', compact('title_name',  'type'));
    }

    public function montrealReturnedData(Datatables $datatables, Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)
            ->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->where('is_custom_route', 0)
            ->where('creator_id', 477260);
       /* if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_returned', compact('record'));
            })
            ->make(true);
    }

    public function montrealreturnedDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_returned_detail', compact('data', 'sprintId'));
    }

    public function montrealReturnedExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->where('creator_id', 477260)->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Joey Returned Scan', 'Hub Returned Scan', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            // $delivered_at = '';
            $returned_at = '';
            $hub_return_scan = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            /* if ($montreal->delivered_at) {
                 $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                 $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                 $delivered_at->format('Y-m-d H:i:s');
             }*/
            if ($montreal->returned_at) {
                $returned_at = new \DateTime($montreal->returned_at, new \DateTimeZone('UTC'));
                $returned_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $returned_at->format('Y-m-d H:i:s');
            }
            if ($montreal->hub_return_scan) {
                $hub_return_scan = new \DateTime($montreal->hub_return_scan, new \DateTimeZone('UTC'));
                $hub_return_scan->setTimeZone(new \DateTimeZone('America/Toronto'));
                $hub_return_scan->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Joey Returned Scan' => $returned_at,
                'Hub Returned Scan' => $hub_return_scan,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Returned Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Returned Data');
            $excel->sheet('Montreal Returned Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getMontNotreturned(Request $request)
    {
        //$route_id = $request->get('route_id');
        // $joey_id = $request->get('joey_id');
        $title_name = 'Montreal';
        $type = 'return';
        return backend_view('newmontrealdashboard.not_returned_orders', compact('title_name',  'type'));
    }

    public function montrealNotReturnedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $today_date = date('Y-m-d', strtotime($today_date . ' -1 days'));

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)
            ->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->where('is_custom_route', 0)
            ->whereNull('hub_return_scan')
            ->where('creator_id', 477260);
        /* if (!empty($request->get('route_id'))) {
             $query = $query->where('route_id', $request->get('route_id'));
         }
         if (!empty($request->get('joey_id'))) {
             $query = $query->where('joey_id', $request->get('joey_id'));
         }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_notreturned', compact('record'));
            })
            ->make(true);
    }

    public function montrealNotReturnedDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_notreturned_detail', compact('data', 'sprintId'));
    }

    public function montrealNotReturnedExcel($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereNull('hub_return_scan')->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->where('creator_id', 477260)->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Joey Returned Scan', 'Hub Returned Scan', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            // $delivered_at = '';
            $returned_at = '';
            $hub_return_scan = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            /* if ($montreal->delivered_at) {
                 $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                 $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                 $delivered_at->format('Y-m-d H:i:s');
             }*/
            if ($montreal->returned_at) {
                $returned_at = new \DateTime($montreal->returned_at, new \DateTimeZone('UTC'));
                $returned_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $returned_at->format('Y-m-d H:i:s');
            }
            if ($montreal->hub_return_scan) {
                $hub_return_scan = new \DateTime($montreal->hub_return_scan, new \DateTimeZone('UTC'));
                $hub_return_scan->setTimeZone(new \DateTimeZone('America/Toronto'));
                $hub_return_scan->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Joey Returned Scan' => $returned_at,
                'Hub Returned Scan' => $hub_return_scan,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Returns Not Received At Hub ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Returns Not Received At Hub');
            $excel->sheet('Returns Not Received At Hub', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function montrealNotReturnedExcelTrackingIds($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $date = date('Y-m-d', strtotime($date . ' -1 days'));

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 0)->whereNull('hub_return_scan')->whereIn('task_status_id', [101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->where('creator_id', 477260)->get();
        $montreal_array[] = ['Amazon tracking #'];
        foreach ($montreal_data as $montreal) {
            $montreal_array[] = [
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1))
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Returns Not Received Tracking ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Returns Not Received Tracking');
            $excel->sheet('Returns Not Received At Hub', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function getMontCustomRoute(Request $request)
    {
        //$route_id = $request->get('route_id');
       // $joey_id = $request->get('joey_id');
        $title_name = 'Montreal';
        $type = 'custom';
        return backend_view('newmontrealdashboard.custom_route', compact('title_name',  'type'));
    }

    public function montrealCustomRouteData(Datatables $datatables, Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");

        $start_dt = new DateTime($today_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($today_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $query = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)
            ->where('is_custom_route', 1)
            ->where('creator_id', 477260);
        /*if (!empty($request->get('route_id'))) {
            $query = $query->where('route_id', $request->get('route_id'));
        }
        if (!empty($request->get('joey_id'))) {
            $query = $query->where('joey_id', $request->get('joey_id'));
        }*/
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
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
                return self::$status[$record->task_status_id];
            })
            ->editColumn('order_image', static function ($record) {
                if (isset($record->order_image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->order_image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos + 1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('newmontrealdashboard.action_custom_route', compact('record'));
            })
            ->make(true);
    }

    public function montrealCustomRouteDetail(Request $request, $id)
    {
        $mont_id = base64_decode($id);
        $amazon_montreal = AmazonEnteries::where(['id' => $mont_id])->get();
        $data = $this->get_trackingorderdetails($amazon_montreal[0]->sprint_id);
        $sprintId = $data['sprintId'];
        $data = $data['data'];

        return backend_view('newmontrealdashboard.montreal_custom_route_detail', compact('data', 'sprintId'));
    }

    public function montrealCustomRouteExcel($date = null)
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


        $montreal_data = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 1)->where('creator_id', 477260)->get();
        $montreal_array[] = ['JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Out For Delivery', 'Sorted Time', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status'];
        foreach ($montreal_data as $montreal) {
            $picked_up_at = '';
            $sorted_at = '';
            $delivered_at = '';
            if ($montreal->picked_up_at) {
                $picked_up_at = new \DateTime($montreal->picked_up_at, new \DateTimeZone('UTC'));
                $picked_up_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $picked_up_at->format('Y-m-d H:i:s');
            }
            if ($montreal->sorted_at) {
                $sorted_at = new \DateTime($montreal->sorted_at, new \DateTimeZone('UTC'));
                $sorted_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $sorted_at->format('Y-m-d H:i:s');
            }
            if ($montreal->delivered_at) {
                $delivered_at = new \DateTime($montreal->delivered_at, new \DateTimeZone('UTC'));
                $delivered_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $delivered_at->format('Y-m-d H:i:s');
            }
            $montreal_array[] = [
                'JoeyCo Order #' => strval($montreal->sprint_id),
                'Route Number' => $montreal->route_id ? strval('R-' . $montreal->route_id . '-' . $montreal->ordinal) : '',
                'Joey' => $montreal->joey_name ? strval($montreal->joey_name . ' (' . $montreal->joey_id . ')') : '',
                'Customer Address' => strval($montreal->address_line_1),
                'Out For Delivery' => $picked_up_at,
                'Sorter Time' => $sorted_at,
                'Actual Arrival @ CX' => $delivered_at,
                'Amazon tracking #' => strval(substr($montreal->tracking_id, ($pos = strrpos($montreal->tracking_id, '_')) == false ? 0 : $pos + 1)),
                'Status' => strval(self::$status[$montreal->task_status_id])
            ];
        }
        $date = date('Y-m-d', strtotime($date . ' +1 days'));
        Excel::create('Montreal Custom Route Data ' . $date . '', function ($excel) use ($montreal_array) {
            $excel->setTitle('Montreal Custom Route Data');
            $excel->sheet('Montreal Custom Route Data', function ($sheet) use ($montreal_array) {
                $sheet->fromArray($montreal_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Montreal Reoute Info
     */
    public function getRouteinfo(Request $request)
    {

        $show_message = $request->message;
        if(!is_null($show_message))
        {
            $current_url  = $request->url();
            $query_string = http_build_query( $request->except(['message'] ) );
            return redirect($current_url.'?'.$query_string)
                ->with('alert-success', $show_message);
        }

        $date = $request->input('datepicker');
        // dd($date);
        if ($date == null) {
            $date = date("Y-m-d");
        }

        $montreal_info = JoeyRoutes::join('joey_route_locations', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->where('joey_routes.date', 'like', $date . "%")
            ->where('joey_routes.hub', 16)
            ->where('joey_routes.deleted_at', null)
            ->where('joey_route_locations.deleted_at', null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
            ->select('joey_routes.*')
            ->get();
        $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
            ->where('is_enable', 1)
            ->whereNull('deleted_at')
            ->get();

        return backend_view('newmontrealdashboard.montreal_route_info', compact('montreal_info','flagCategories'));
    }

    /**
     * Get Montreal Route Info excel report
     */
    public function montrealRouteinfoExcel($date = null)
    {
        //setting up current date if null
        if ($date == null) {
            $date = date('Y-m-d');
        }

        /*getting csv file data*/
        $montreal_route_data = JoeyRoutes::join('joey_route_locations', 'joey_routes.id', '=', 'joey_route_locations.route_id')
            ->where('joey_routes.date', 'like', $date . "%")
            ->where('joey_routes.hub', 16)
            ->where('joey_routes.deleted_at', null)
            ->where('joey_route_locations.deleted_at', null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
            ->select('joey_routes.*')
            ->get();

        //checking if data is null then return null
        if (count($montreal_route_data) <= 0) {
            // if the data null ten return empty array
            return [];
        }

        // init data variable
        $data = [];
        $csv_header = ['Route No', 'Joey Name', 'No of drops', 'No of picked', 'No of drops completed', 'No of Returns', 'No of unattempted'];
        $data[0] = $csv_header;

        $iteration = 1;
        foreach ($montreal_route_data as $montreal_route) {
            $joey_name = ($montreal_route->joey) ? $montreal_route->Joey->first_name . ' ' . $montreal_route->Joey->last_name : '';
            $data[$iteration] = [
                $montreal_route->id,
                $joey_name,
                $montreal_route->TotalOrderDropsCount(),
                $montreal_route->TotalOrderPickedCount(),
                $montreal_route->TotalOrderDropsCompletedCount(),
                $montreal_route->TotalOrderReturnCount(),
                $montreal_route->TotalOrderUnattemptedCount()
            ];
            $iteration++;
        }
        return $data;
    }

    /**
     * Get Montreal HubRoute Edit
     */
    public function montrealHubRouteEdit(Request $request, $routeId, $hubId)
    {

        $tracking_id = null;
        $status = null;
        $route = JoeyRouteLocations::join('sprint__tasks', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->leftJoin('merchantids', 'merchantids.task_id', '=', 'sprint__tasks.id')
            ->join('locations', 'location_id', '=', 'locations.id')
            ->join('sprint__sprints', 'sprint_id', '=', 'sprint__sprints.id')
            ->leftJoin('sprint__contacts', 'sprint__contacts.id', '=', 'sprint__tasks.contact_id')
            ->whereNull('sprint__sprints.deleted_at')
            //->whereNotIn('sprint__sprints.status_id',[36,17])
            ->where('route_id', '=', $routeId)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('joey_route_locations.ordinal', 'asc');
        if (!empty($request->get('tracking-id'))) {
            $tracking_id = $request->get('tracking-id');
            $route = $route->where('merchantids.tracking_id', '=', $request->get('tracking-id'));
        }

        if (!empty($request->get('status'))) {
            $status = $request->get('status');
            $route = $route->where('sprint__sprints.status_id', '=', $request->get('status'));
        }
        $route = $route->get(['joey_route_locations.id', 'merchantids.merchant_order_num', 'joey_route_locations.task_id', 'merchantids.tracking_id',
            'sprint_id', 'type', 'start_time', 'end_time', 'address', 'postal_code'
            , 'joey_route_locations.arrival_time', 'joey_route_locations.finish_time', 'sprint__sprints.status_id', 'sprint__tasks.sprint_id', 'sprint__sprints.creator_id',
            'joey_route_locations.distance', 'sprint__contacts.name', 'sprint__contacts.phone', 'joey_route_locations.route_id', 'joey_route_locations.ordinal']);

        $checkJoey=JoeyRoutes::where('id', $routeId)->whereNull('deleted_at')->whereNotNull('joey_id')->first();
        $joey=null;
        if($checkJoey!=null){
            $joey= $checkJoey->joey??null;
        }
        return backend_view('newmontrealdashboard.edit-hub-route', ['route' => $route, 'hub_id' => $hubId, 'tracking_id' => $tracking_id, 'status_select' => $status, 'joey' => $joey]);
    }

    /**
     * Render Model flag history table view
     */
    public function flagHistoryModelHtmlRender(Request $request)
    {
        $request_data = $request->all();

        $joey_flags_history = FlagHistory::where('sprint_id',$request_data['sprint'])
            ->orderBy('id', 'DESC')
            ->where('unflaged_by','=',0)
            ->get();

        //getting flag categories
        $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
            ->where('is_enable', 1)
            ->whereNull('deleted_at')
            ->get();

        $html =  view('backend.newmontrealdashboard.sub-views.ajax-render-view-edit-hub-route-flag-model',
            compact(
                'joey_flags_history',
                'flagCategories',
                'request_data'

            )
        )->render();

        return response()->json(['status' => true,'html'=>$html]);
    }

    /**
     * Get Montreal Tracking Order
     */
    public function getMontrealtrackingorderdetails($sprintId)
    {
        $data = $this->get_trackingorderdetails($sprintId);
        $sprintId = $data['sprintId'];
        $data = $data['data'];
        return backend_view('newmontrealdashboard.orderdetailswtracknigid', compact('data', 'sprintId'));
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
                'joeys.last_name as joey_lastname', 'vendors.first_name as merchant_firstname', 'vendors.last_name as merchant_lastname', 'merchantids.scheduled_duetime'
            , 'joeys.id as joey_id', 'merchantids.tracking_id', 'joeys.phone as joey_contact', 'joey_route_locations.ordinal as stop_number', 'merchantids.merchant_order_num', 'merchantids.address_line2', 'sprint__sprints.creator_id'));

        $i = 0;

        $data = [];

        foreach ($result as $tasks) {
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] = $tasks;
            $taskHistory = TaskHistory::where('sprint_id', '=', $tasks->sprint_id)->WhereNotIn('status_id', [17, 38])->orderBy('date')
                //->where('active','=',1)
                ->get(['status_id', \DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id', '=', $tasks->sprint_id)->orderBy('created_at')
                ->first();

            if (!empty($returnTOHubDate)) {
                $taskHistoryre = TaskHistory::where('sprint_id', '=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id', [17, 38])->orderBy('date')
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
                    $taskHistoryre = TaskHistory::where('sprint_id', '=', $returnTO2->reattempt_of)->WhereNotIn('status_id', [17, 38])->orderBy('date')
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
    }
	
	public function addNote(Request $request)
    {
        $data=$request->all();
        
            $route=JoeyRoutes::where('id', $data['routeId'])->whereNull('deleted_at')->whereNotNull('joey_id')->first();
            if (isset($route->joey_id) && $route->joey_id!=null) {
                $deviceIds = UserDevice::where('user_id', $route->joey_id)->pluck('device_token');
                $subject = 'Customer Support';
                $message = $data['note'];
                Fcm::sendPush($subject, $message, 'trackingnote', null, $deviceIds);
                $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'trackingnote'],
                    'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'trackingnote']];
                $createNotification = [
                    'user_id' => $route->joey_id,
                    'user_type' => 'Joey',
                    'notification' => $subject,
                    'notification_type' => 'trackingnote',
                    'notification_data' => json_encode(["body" => $message]),
                    'payload' => json_encode($payload),
                    'is_silent' => 0,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                UserNotification::create($createNotification);
                TrackingNote::create(['user_id'=>Auth::id(),'tracking_id'=>$data['tracking_id'],'note'=>$data['note'],'type'=>'dashboard']);
            }
    }
    public function getNotes(Request $request)
    {
        $tracking_id=$request->get('tracking_id');

        $notes=TrackingNote::with('dashboard','joey')->where('tracking_id',$tracking_id)->orderBy('created_at',"ASC")->get()->toArray();
        return $notes;
    }
}
