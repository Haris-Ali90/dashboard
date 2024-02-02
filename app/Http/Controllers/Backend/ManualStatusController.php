<?php

namespace App\Http\Controllers\Backend;

use App\Http\Traits\BasicModelFunctions;
use App\JoeyRouteLocations;
use App\JoeyRoutes;
use App\Sprint;
use App\MerchantIds;
use App\SprintReattempt;
use App\SprintTaskHistory;
use App\TrackingDelay;
use App\TrackingImageHistory;
use Illuminate\Http\Request;
use App\Ctc;
use App\Task;
use App\Notes;
use App\Ctc_count;
use App\CtcVendor;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;


class ManualStatusController extends BackendController
{

    use BasicModelFunctions;
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
        "255" => 'Order Delay',
        "145" => 'Returned To Merchant',
        "146" => "Delivery Missorted, Incorrect Address",
        '147' => 'Scanned at Hub',
        '148' => 'Scanned at Hub and labelled',
        '149' => 'pick from hub',
        '150' => 'drop to other hub',
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route',
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
            "255" => 'Order Delay',
            "145" => 'Returned To Merchant',
            "146" => "Delivery Missorted, Incorrect Address",
            '147' => 'Scanned at Hub',
            '148' => 'Scanned at Hub and labelled',
            '149' => 'pick from hub',
            '150' => 'drop to other hub',
            '153' => 'Miss sorted to be reattempt',
            '154' => 'Joey unable to complete the route',
            '155' => 'To be re-attempted tomorrow');
        return $statusid[$id];
    }


    /**
     * Get CTC Dashboard
     */
    public function getManualStatus(Request $request)
    {
        $custom_filteration = $request->input('filter_custom');
        return backend_view('manualStatus.index',compact('custom_filteration'));
    }

    /**
     * Yajra call after  CTC Dashboard
     */
    public function ManualStatusData(Datatables $datatables, Request $request)
    {
        $filter_data = $request->input('filter_custom');

        $tracking_id = !empty($request->get('tracking_id')) ? $request->get('tracking_id') : null;


        if ($filter_data == 'dispatch') {
            $dispatch_filter = TrackingImageHistory::whereNotNull('route_id');
        }
        elseif($filter_data == 'tracking_ids')
        {
            $dispatch_filter =  TrackingImageHistory::where('tracking_id', $tracking_id)->whereNull('route_id')->whereNull('plan_id');
        }
        elseif($filter_data == 'plan')
        {
            $dispatch_filter = TrackingImageHistory::whereNotNull('plan_id');
        }
        else
        {
            $dispatch_filter = TrackingImageHistory::where('tracking_id', $tracking_id)->whereNull('plan_id')->whereNull('route_id');
        }

        $query = $dispatch_filter->orderBy('created_at','desc');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status_id', static function ($record) {
                if (isset($record->status_id))
                {
                    $current_status = $record->status_id;
                    if ($current_status == 13) {
                        return "At hub Processing";
                    } else {
                        return self::$status[$current_status];
                    }
                }
                else
                {
                    return '';
                }
            })
            ->editColumn('attachment_path', static function ($record) {
                if (isset($record->attachment_path)) {
                    return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $record->attachment_path . '" />';
                } else {
                    return '';
                }
            })
            ->editColumn('reason_id', static function ($record) {
                if (isset($record->reason)) {
                    return $record->reason->title;
                } else {
                    return '';
                }
            })
            ->editColumn('created_at', static function ($record) {
                if ($record->created_at) {
                    $created_at = new \DateTime($record->created_at, new \DateTimeZone('UTC'));
                    $created_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    return $created_at->format('Y-m-d H:i:s');
                } else {
                    return '';
                }
            })
            ->editColumn('user_id', static function ($record) {
                if ($record->domain == 'finance')
                {
                    if (isset($record->FinanceUser)) {
                        return $record->FinanceUser->full_name;
                    } else {
                        return '';
                    }
                }
                else
                {
                    if (isset($record->user)) {
                        return $record->user->full_name;
                    } else {
                        return '';
                    }
                }

            })
            ->editColumn('route_id', static function ($record) {
                if (isset($record->route_id)) {
                    return $record->route_id;
                } else {
                    return '';
                }
            })

            ->make(true);
    }



}
