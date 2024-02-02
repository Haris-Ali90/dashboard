<?php

namespace App\Http\Controllers\Backend;

use App\JoeyRouteLocations;
use App\JoeyRoutes;
use App\Sprint;
use App\SprintReattempt;
use App\SprintTaskHistory;
use Illuminate\Http\Request;
use App\Amazon;
use App\Amazon_count;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\Builder;

class OttawaController extends BackendController
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
        "255" => 'Order Delay',
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
            "110" => "Delivery to hub for re-delivery",
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
            "255" => 'Order Delay',
            "145" => 'Returned To Merchant',
            "146" => "Delivery Missorted, Incorrect Address",
            '153' => 'Miss sorted to be reattempt',
            '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
            '155' => 'To be re-attempted tomorrow');
        return $statusid[$id];
    }

    /**
     * Get Ottawa dashboard
     */
    public function getOttawa(Request $request)
    {
        $today_date = !empty($request->get('datepicker'))?$request->get('datepicker'):date("Y-m-d");
        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
			$ottawa_notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477282])
            ->whereIn('status_id', [61,13])
            ->count();
        return backend_view('ottawadashboard.ottawa_dashboard',compact('amazon_count','ottawa_notscan_count'));

    }

    /**
     * Yajra call after Ottawa dashboard
     */
    public function ottawaData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('updated_at','DESC');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if(isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "'.$record->image.'" />';
                }
                else{
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ottawadashboard.action', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Ottawa dashboard orders excel report
     */
    public function ottawaExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $ottawa_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['vendor_id' => 477282])->get();
        $ottawa_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($ottawa_data as $ottawa) {
            $ottawa_array[] = array(
                'JoeyCo Order #' => $ottawa->order_id,
                'Route Number' => $ottawa->route,
                'Joey' => $ottawa->joey,
                'Customer Address' => $ottawa->address,
                'Pickup From Hub' => $ottawa->picked_hub_time,
                'Sorter Time' => $ottawa->sorter_time,
                'Estimated Delivery ETA' => $ottawa->dropoff_eta,
                'Actual Arrival @ CX' => $ottawa->delivery_time,
                'Amazon tracking #' =>  substr($ottawa->tracking_id, ($pos = strrpos($ottawa->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$ottawa->sprint_status]
            );
        }
        Excel::create('Ottawa Data '.$date.'', function ($excel) use ($ottawa_array) {
            $excel->setTitle('Ottawa Data');
            $excel->sheet('Ottawa Data', function ($sheet) use ($ottawa_array) {
                $sheet->fromArray($ottawa_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Ottawa dashboard order detail
     */
    public function ottawaProfile(Request $request, $id)
    {
        $ottawa_id = base64_decode($id);
        $amazon_ottawa = Amazon::where(['id' => $ottawa_id])->get();
        $amazon_ottawa = $amazon_ottawa[0];

        return backend_view('ottawadashboard.ottawa_profile', compact('amazon_ottawa'));
    }

    /**
     * Get Ottawa Sorted
     */
    public function getOttawatsort(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();
 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
$ottawa_notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477282])
            ->whereIn('status_id', [61,13])
            ->count();

        $title_name = 'Ottawa';
        return backend_view('ottawadashboard.sorted_order', compact('amazon_count', 'title_name','ottawa_notscan_count'));
    }

    /**
     * Yajra call after Ottawa Sorted
     */
    public function ottawaSortedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 133, 'vendor_id' => 477282]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ottawadashboard.action_sorted', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Ottawa Sorted order detail
     */
    public function ottawasortedDetail(Request $request, $id)
    {
        $ottawa_id = base64_decode($id);
        $amazon_ottawa = Amazon::where(['id' => $ottawa_id])->get();
        $amazon_ottawa = $amazon_ottawa[0];

        return backend_view('ottawadashboard.ottawa_sorted_detail', compact('amazon_ottawa'));
    }

    /**
     * Get Ottawa Sorted orders excel reprot
     */
    public function ottawaSortedExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $ottawa_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['sprint_status' => 133, 'vendor_id' => 477282])->get();
        $ottawa_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($ottawa_data as $ottawa) {
            $ottawa_array[] = array(
                'JoeyCo Order #' => $ottawa->order_id,
                'Route Number' => $ottawa->route,
                'Joey' => $ottawa->joey,
                'Customer Address' => $ottawa->address,
                'Pickup From Hub' => $ottawa->picked_hub_time,
                'Sorter Time' => $ottawa->sorter_time,
                'Estimated Delivery ETA' => $ottawa->dropoff_eta,
                'Actual Arrival @ CX' => $ottawa->delivery_time,
                'Amazon tracking #' => substr($ottawa->tracking_id, ($pos = strrpos($ottawa->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$ottawa->sprint_status]
            );
        }
        Excel::create('Ottawa Sorted Data '.$date.'', function ($excel) use ($ottawa_array) {
            $excel->setTitle('Ottawa Sorted Data');
            $excel->sheet('Ottawa Sorted Data', function ($sheet) use ($ottawa_array) {
                $sheet->fromArray($ottawa_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Ottawa Picked Up
     */
    public function getOttawathub(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
$ottawa_notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477282])
            ->whereIn('status_id', [61,13])
            ->count();

        $title_name = 'Ottawa';
        return backend_view('ottawadashboard.pickup_hub', compact('amazon_count', 'title_name','ottawa_notscan_count'));
    }

    /**
     * Yajra call after Ottawa Picked Up
     */
    public function ottawaPickedUpData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->where(['sprint_status' => 121, 'vendor_id' => 477282]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ottawadashboard.action_pickup', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Ottawa Picked Up order detail
     */
    public function ottawapickupDetail(Request $request, $id)
    {
        $ottawa_id = base64_decode($id);
        $amazon_ottawa = Amazon::where(['id' => $ottawa_id])->get();
        $amazon_ottawa = $amazon_ottawa[0];

        return backend_view('ottawadashboard.ottawa_pickup_detail', compact('amazon_ottawa'));
    }

    /**
     * Get Ottawa Picked Up orders excel report
     */
    public function ottawaPickedUpExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $ottawa_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->where(['sprint_status' => 121, 'vendor_id' => 477282])->get();
        $ottawa_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($ottawa_data as $ottawa) {
            $ottawa_array[] = array(
                'JoeyCo Order #' => $ottawa->order_id,
                'Route Number' => $ottawa->route,
                'Joey' => $ottawa->joey,
                'Customer Address' => $ottawa->address,
                'Pickup From Hub' => $ottawa->picked_hub_time,
                'Sorter Time' => $ottawa->sorter_time,
                'Estimated Delivery ETA' => $ottawa->dropoff_eta,
                'Actual Arrival @ CX' => $ottawa->delivery_time,
                'Amazon tracking #' => substr($ottawa->tracking_id, ($pos = strrpos($ottawa->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$ottawa->sprint_status]
            );
        }
        Excel::create('Ottawa Picked Up Data '.$date.'', function ($excel) use ($ottawa_array) {
            $excel->setTitle('Ottawa Picked Up Data');
            $excel->sheet('Ottawa Picked Up Data', function ($sheet) use ($ottawa_array) {
                $sheet->fromArray($ottawa_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Ottawa Not Scan
     */
    public function getOttawatnotscan(Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
$ottawa_notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477282])
            ->whereIn('status_id', [61,13])
            ->count();

        $title_name = 'Ottawa';
        return backend_view('ottawadashboard.not_scanned_orders', compact('amazon_count', 'title_name','ottawa_notscan_count'));
    }

    /**
     * Yajra call after Ottawa Not Scan
     */
    public function ottawaNotScanData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->whereIn('sprint_status' , [61,13])
            ->where(['vendor_id' => 477282]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ottawadashboard.action_notscan', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Ottawa Not Scan order detail
     */
    public function ottawanotscanDetail(Request $request, $id)
    {
        $ottawa_id = base64_decode($id);
        $amazon_ottawa = Amazon::where(['id' => $ottawa_id])->get();
        $amazon_ottawa = $amazon_ottawa[0];

        return backend_view('ottawadashboard.ottawa_notscan_detail', compact('amazon_ottawa'));
    }

    /**
     * Get Ottawa Not Scan orders excel reprort
     */
    public function ottawaNotscanExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $ottawa_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereIn('sprint_status' , [61,13])
            ->where(['vendor_id' => 477282])->get();
        $ottawa_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($ottawa_data as $ottawa) {
            $ottawa_array[] = array(
                'JoeyCo Order #' => $ottawa->order_id,
                'Route Number' => $ottawa->route,
                'Joey' => $ottawa->joey,
                'Customer Address' => $ottawa->address,
                'Pickup From Hub' => $ottawa->picked_hub_time,
                'Sorter Time' => $ottawa->sorter_time,
                'Estimated Delivery ETA' => $ottawa->dropoff_eta,
                'Actual Arrival @ CX' => $ottawa->delivery_time,
                'Amazon tracking #' => substr($ottawa->tracking_id, ($pos = strrpos($ottawa->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$ottawa->sprint_status]
            );
        }
        Excel::create('Ottawa Not Scan Data '.$date.'', function ($excel) use ($ottawa_array) {
            $excel->setTitle('Ottawa Not Scan Data');
            $excel->sheet('Ottawa Not Scan Data', function ($sheet) use ($ottawa_array) {
                $sheet->fromArray($ottawa_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Ottawa Delivered
     */
    public function getOttawadelivered(Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();
			 $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
$ottawa_notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477282])
            ->whereIn('status_id', [61,13])
            ->count();
        $title_name = 'Ottawa';
        return backend_view('ottawadashboard.delivered_orders', compact('amazon_count', 'title_name','ottawa_notscan_count'));
    }

    /**
     * Yajra call after Ottawa Delivered
     */
    public function ottawaDeliveredData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")->whereNotIn('sprint_status',  [13,124,133, 121, 61])->where('vendor_id', [477282]);
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ottawadashboard.action_delivered', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Ottawa Delivered order detail
     */
    public function ottawadeliveredDetail(Request $request, $id)
    {
        $ottawa_id = base64_decode($id);
        $amazon_ottawa = Amazon::where(['id' => $ottawa_id])->get();
        $amazon_ottawa = $amazon_ottawa[0];

        return backend_view('ottawadashboard.ottawa_delivered_detail', compact('amazon_ottawa'));
    }

    /**
     * Get Ottawa Delivered orders excel report
     */
    public function ottawaDeliveredExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $ottawa_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereNotIn('sprint_status',  [13,124,133, 121, 61])->where('vendor_id', [477282])->get();
        $ottawa_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($ottawa_data as $ottawa) {
            $ottawa_array[] = array(
                'JoeyCo Order #' => $ottawa->order_id,
                'Route Number' => $ottawa->route,
                'Joey' => $ottawa->joey,
                'Customer Address' => $ottawa->address,
                'Pickup From Hub' => $ottawa->picked_hub_time,
                'Sorter Time' => $ottawa->sorter_time,
                'Estimated Delivery ETA' => $ottawa->dropoff_eta,
                'Actual Arrival @ CX' => $ottawa->delivery_time,
                'Amazon tracking #' => substr($ottawa->tracking_id, ($pos = strrpos($ottawa->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$ottawa->sprint_status]
            );
        }
        Excel::create('Ottawa Delivered Data '.$date.'', function ($excel) use ($ottawa_array) {
            $excel->setTitle('Ottawa Delivered Data');
            $excel->sheet('Ottawa Delivered Data', function ($sheet) use ($ottawa_array) {
                $sheet->fromArray($ottawa_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Ottawa Delivered
     */
    public function getOttawareturned(Request $request)
    {

        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $amazon_count = Amazon_count::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->where(['vendor_id' => 477282])
            ->orderBy('id','DESC')
            ->first();

        $date = date('Y-m-d', strtotime($today_date. ' -1 days'));
        $ottawa_notscan_count = Sprint::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477282])
            ->whereIn('status_id', [61,13])
            ->count();
        $title_name = 'Ottawa';
        return backend_view('ottawadashboard.returned_orders', compact('amazon_count', 'title_name','ottawa_notscan_count'));
    }

    /**
     * Yajra call after Ottawa Delivered
     */
    public function ottawaReturnedData(Datatables $datatables, Request $request)
    {
        $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $query = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $today_date . "%")
            ->whereIn('sprint_status',  [101,102,103,104,105,106,107,108,109,110,111,112,131,135,136])
            ->where('vendor_id', [477282]);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('sprint_status', static function ($record) {
                return self::$status[$record->sprint_status];
            })
            ->editColumn('image', static function ($record) {
                if (isset($record->image)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->image . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('tracking_id', static function ($record) {
                return substr($record->tracking_id, ($pos = strrpos($record->tracking_id, '_')) == false ? 0 : $pos+1);
            })
            ->addColumn('action', static function ($record) {
                return backend_view('ottawadashboard.action_returned', compact('record'));
            })
            ->make(true);
    }

    /**
     * Get Ottawa Delivered order detail
     */
    public function ottawareturnedDetail(Request $request, $id)
    {
        $ottawa_id = base64_decode($id);
        $amazon_ottawa = Amazon::where(['id' => $ottawa_id])->get();
        $amazon_ottawa = $amazon_ottawa[0];

        return backend_view('ottawadashboard.ottawa_returned_detail', compact('amazon_ottawa'));
    }

    /**
     * Get Ottawa Delivered orders excel report
     */
    public function ottawaReturnedExcel($date = null)
    {
        if($date == null)
        {
            $date = date('Y-m-d');
        }
        $ottawa_data = Amazon::where(\DB::raw("CONVERT_TZ(updated_at,'UTC','America/Toronto')"), 'like', $date . "%")
            ->whereIn('sprint_status',  [101,102,103,104,105,106,107,108,109,110,111,112,131,135,136])
            ->where('vendor_id', [477282])->get();
        $ottawa_array[] = array('JoeyCo Order #', 'Route Number', 'Joey', 'Customer Address', 'Pickup From Hub', 'Sorter Time', 'Estimated Delivery ETA', 'Actual Arrival @ CX', 'Amazon tracking #', 'Status');
        foreach ($ottawa_data as $ottawa) {
            $ottawa_array[] = array(
                'JoeyCo Order #' => $ottawa->order_id,
                'Route Number' => $ottawa->route,
                'Joey' => $ottawa->joey,
                'Customer Address' => $ottawa->address,
                'Pickup From Hub' => $ottawa->picked_hub_time,
                'Sorter Time' => $ottawa->sorter_time,
                'Estimated Delivery ETA' => $ottawa->dropoff_eta,
                'Actual Arrival @ CX' => $ottawa->delivery_time,
                'Amazon tracking #' => substr($ottawa->tracking_id, ($pos = strrpos($ottawa->tracking_id, '_')) == false ? 0 : $pos+1),
                'Status' =>   self::$status[$ottawa->sprint_status]
            );
        }
        Excel::create('Ottawa Returned Data '.$date.'', function ($excel) use ($ottawa_array) {
            $excel->setTitle('Ottawa Returned Data');
            $excel->sheet('Ottawa Returned Data', function ($sheet) use ($ottawa_array) {
                $sheet->fromArray($ottawa_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    /**
     * Get Ottawa Route info
     */
    public function getRouteinfo(Request $request)
    {
        $date = $request->input('datepicker');
        // dd($date);
        if($date==null){
            $date = date("Y-m-d");
        }

        /*$ottawa_info = JoeyRoutes::has('RouteLocarions', function (Builder $query) {
               $query->where('deleted_at','=',null);
            })
			->where('date','like',$date."%")
            ->where('hub',19)
			->where('deleted_at',null)
            ->orderBy('id', 'ASC')
            ->get();*/
			$ottawa_info = JoeyRoutes::join('joey_route_locations','joey_routes.id','=','joey_route_locations.route_id')
            ->where('joey_routes.date','like',$date."%")
            ->where('joey_routes.hub',19)
            ->where('joey_routes.deleted_at',null)
            ->where('joey_route_locations.deleted_at',null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
			->select('joey_routes.*')
            ->get();

        return backend_view('ottawadashboard.ottawa_route_info', compact('ottawa_info'));
    }

    /**
     * Get Ottawa Route info excel rerpot
     */
    public function ottawaRouteinfoExcel($date = null)
    {
        //setting up current date if null
        if($date == null)
        {
            $date = date('Y-m-d');
        }

        /*getting csv file data*/
        $montreal_route_data = JoeyRoutes::join('joey_route_locations','joey_routes.id','=','joey_route_locations.route_id')
            ->where('joey_routes.date','like',$date."%")
            ->where('joey_routes.hub',19)
            ->where('joey_routes.deleted_at',null)
            ->where('joey_route_locations.deleted_at',null)
            ->orderBy('joey_routes.id', 'ASC')
            ->groupBy('joey_routes.id')
			->select('joey_routes.*')
            ->get();
		//JoeyRoutes::where(\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto')"),'like',$date."%")->where('hub',19)->get();

        //checking if data is null then return null
        if(count($montreal_route_data) <= 0)
        {
            // if the data null ten return empty array
            return [];
        }

        // init data variable
        $data  = [];
        $csv_header = ['Route No','Joey Name', 'No of drops', 'No of picked', 'No of drops completed', 'No of Returns', 'No of unattempted'];
        $data[0] =  $csv_header;

        $iteration = 1;
        foreach($montreal_route_data as $montreal_route)
        {
			$joey_name = ($montreal_route->joey)?$montreal_route->Joey->first_name.' '.$montreal_route->Joey->last_name:'';
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
     * Get Ottawa Hub Route Edit
     */
    public function ottawaHubRouteEdit(Request $request,$routeId,$hubId){

        $tracking_id = null;
        $status =null;
        $route = JoeyRouteLocations::join('sprint__tasks','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->join('sprint__sprints','sprint_id','=','sprint__sprints.id')
            ->leftJoin('sprint__contacts','sprint__contacts.id','=','sprint__tasks.contact_id')
            ->whereNull('sprint__sprints.deleted_at')
           // ->whereNotIn('sprint__sprints.status_id',[36,17])
            ->where('route_id','=',$routeId)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotNull('merchantids.tracking_id')
            ->orderBy('joey_route_locations.ordinal','asc');
         if (!empty($request->get('tracking-id'))) {
             $tracking_id = $request->get('tracking-id');
             $route = $route->where('merchantids.tracking_id', '=', $request->get('tracking-id'))
             ;
         }

         if (!empty($request->get('status'))) {
             $status = $request->get('status');
             $route = $route->where('sprint__sprints.status_id', '=', $request->get('status'));
         }
         $route =$route->get(['joey_route_locations.id','merchantids.merchant_order_num','joey_route_locations.task_id','merchantids.tracking_id',
                'sprint_id','type','start_time','end_time','address','postal_code'
                ,'joey_route_locations.arrival_time','joey_route_locations.finish_time', 'sprint__sprints.status_id','sprint__tasks.sprint_id',
                'joey_route_locations.distance','sprint__contacts.name','sprint__contacts.phone','joey_route_locations.route_id','joey_route_locations.ordinal']);

        return backend_view('ottawadashboard.edit-hub-route',['route'=>$route,'hub_id'=>$hubId,'tracking_id'=>$tracking_id,'status_select'=>$status]);
    }

    /**
     * Get Ottawa Tracking order
     */
    public function getOttawatrackingorderdetails($sprintId)
    {
        $result= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
            ->leftJoin('joeys','joeys.id','=','joey_routes.joey_id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
            ->leftJoin('vendors','creator_id','=','vendors.id')
            ->where('sprint__tasks.sprint_id','=',$sprintId)
            //->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal','DESC')->take(1)
            ->get(array('sprint__tasks.*','joey_routes.id as route_id','locations.address','locations.suite','locations.postal_code','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email',
                'joeys.first_name as joey_firstname','joeys.id as joey_id',
                'joeys.last_name as joey_lastname','vendors.first_name as merchant_firstname','vendors.last_name as merchant_lastname','merchantids.scheduled_duetime'
            ,'joeys.id as joey_id','merchantids.tracking_id','joeys.phone as joey_contact','joey_route_locations.ordinal as stop_number'));

        $i=0;

        $data = [];

        foreach($result as $tasks){
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] =  $tasks;
            $taskHistory= SprintTaskHistory::where('sprint_id','=',$tasks->sprint_id)->WhereNotIn('status_id',[17,38])->orderBy('id')
                //->where('active','=',1)
                ->get(['status_id','created_at']);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id','=' ,$tasks->sprint_id)->orderBy('created_at')
                ->first();

            if(!empty($returnTOHubDate))
            {
                $taskHistoryre= SprintTaskHistory::where('sprint_id','=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id',[17,38])->orderBy('id')
                    //->where('active','=',1)
                    ->get(['status_id','created_at']);

                foreach ($taskHistoryre as $history){

                    $status[$history->status_id]['id'] = $history->status_id;
                    if($history->status_id==13)
                    {
                        $status[$history->status_id]['description'] ='At hub - processing';
                    }
                    else
                    {
                        $status[$history->status_id]['description'] =$this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

                }

            }
            if(!empty($returnTOHubDate))
            {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id','=' , $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if(!empty($returnTO2))
                {
                    $taskHistoryre= SprintTaskHistory::where('sprint_id','=',$returnTO2->reattempt_of)->WhereNotIn('status_id',[17,38])->orderBy('id')
                        //->where('active','=',1)
                        ->get(['status_id','created_at']);

                    foreach ($taskHistoryre as $history){

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if($history->status_id==13)
                        {
                            $status2[$history->status_id]['description'] ='At hub - processing';
                        }
                        else
                        {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history){

                $status1[$history->status_id]['id'] = $history->status_id;

                if($history->status_id==13)
                {
                    $status1[$history->status_id]['description'] ='At hub - processing';
                }
                else
                {
                    $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                }
                $status1[$history->status_id]['created_at'] = date('Y-m-d H:i:s',strtotime($history->created_at)-14400);

            }
            $data[$i]['status']= $status;
            $data[$i]['status1']= $status1;
            $data[$i]['status2']=$status2;
            $i++;
        }
        return backend_view('ottawadashboard.orderdetailswtracknigid',['data'=>$data,'sprintId' => $sprintId]);
    }
}
