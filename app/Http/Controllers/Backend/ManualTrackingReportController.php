<?php

namespace App\Http\Controllers\Backend;

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Classes\RestAPI;
use Illuminate\Http\Request;
use App\TrackingImageHistory;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\BasicModelFunctions;
use Illuminate\Support\Facades\Response;


class ManualTrackingReportController extends BackendController
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
            "255" => "Order Delay",
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


    /**
     * View for mannual tracking report
    */
    public function getManualTrackingReport(Request $request)
    {
       // dd('yes');
      
        return backend_view('manual-tracking-reports.index');
    }

    /**
     * Yajra call after  mannual tracking report
     */
    public function ManualTrackingData(Datatables $datatables, Request $request)
    {

        // $today_date = !empty($request->get('datepicker')) ? $request->get('datepicker') : date("Y-m-d");
        $from_date = !empty($request->get('datepicker')) ? $request->get('datepicker').' 00:00:00' : date("Y-m-d").' 00:00:00';
        $to_date = !empty($request->get('datepicker2')) ? $request->get('datepicker2').' 23:59:59' : date("Y-m-d").' 23:59:59';

        //$query = TrackingImageHistory::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $today_date . "%");
        $query = TrackingImageHistory::whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),  [$from_date, $to_date])->orderBy('created_at');
            //->orderBy('created_at','desc');

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

            ->make(true);

    }

    /**
     * Get Manual Tracking Report excel report
     */
    public function manualTrackingExcel($date = null,$todate=null)
    {
        // dd($todate);
        if($date == null)
        {
            $date = date('Y-m-d').' 00:00:00';
        }
        if($todate == null)
        {
            $todate = date('Y-m-d').' 23:59:59';
        }
        // $manual_tracking_data = TrackingImageHistory::where(\DB::raw("CONVERT_TZ(created_At,'UTC','America/Toronto')"), 'like', $date . "%")
        //     //->orderBy('created_at','desc')
        //     ->get();
        $manual_tracking_data = TrackingImageHistory::whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),  [$date, $todate])
                                                    ->orderBy('created_at')
                                                    ->get();
        $manual_tracking_array[] = array('Tracking #', 'Status', 'Reason', 'User', 'Domain', 'Created At');
        foreach ($manual_tracking_data as $tracking_data) {
            $created_at = '';
            if ($tracking_data->created_at) {
                $created_at = new \DateTime($tracking_data->created_at, new \DateTimeZone('UTC'));
                $created_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $created_at->format('Y-m-d H:i:s');
            }

            if ($tracking_data->domain == 'finance')
            {
                if (isset($tracking_data->FinanceUser)) {
                    $userName = $tracking_data->FinanceUser->full_name;
                } else {
                    $userName = '';
                }
            }
            else
            {
                if (isset($tracking_data->user)) {
                    $userName = $tracking_data->user->full_name;
                } else {
                    $userName = '';
                }
            }

            if (isset($tracking_data->status_id))
            {
                $current_status = $tracking_data->status_id;
                if ($current_status == 13) {
                    $statusName = "At hub Processing";
                } else {
                    $statusName = self::$status[$current_status];
                }
            }
            else
            {
                $statusName = '';
            }


            $manual_tracking_array[] = array(
                'Tracking #' => $tracking_data->tracking_id,
                'Status' =>   $statusName,
                'Reason' => isset($tracking_data->reason) ? $tracking_data->reason->title : '',
                'User' =>  $userName,
                'Domain' => $tracking_data->domain,
                'Created At' => $created_at
            );
        }
        Excel::create('Mannual Tracking Report '.$date.'', function ($excel) use ($manual_tracking_array) {
            $excel->setTitle('Mannual Tracking Report Data');
            $excel->sheet('Mannual Tracking Report Data', function ($sheet) use ($manual_tracking_array) {
                $sheet->fromArray($manual_tracking_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

    public function downloadCsv(Request $request)
    {
         // getting date from request
        $date = !empty($request->fromdatepicker) ? $request->fromdatepicker." 00:00:00" : date("Y-m-d").' 00:00:00';
        $todate = !empty($request->todatepicker) ? $request->todatepicker." 23:59:59"  : date("Y-m-d").' 23:59:59';
        // if($date == null)
        // {
        //     $date = date('Y-m-d').' 00:00:00';
        // }
        // if($todate == null)
        // {
        //     $todate = date('Y-m-d').' 23:59:59';
        // }

        // getting limit
        $limit = ($request->limit) ? (int) $request->limit : 500;

        // creating metaData
        $metaData = $request->all();

        // creatting file name if not exsit in request
        $file_name = (isset($metaData['file_name'])) ? $metaData['file_name'] :'Mannual Tracking Report '.date('Y-m-d',strtotime($date)).' - '.date('Y-m-d',strtotime($todate)).'-('.uniqid().').csv';

        // update metaData with file name
        $metaData['file_name'] = $file_name;

        // creating file path
        $path = public_path().'/images/profile_images/'.$file_name;

        //creating download path
        $metaData['downloadPath'] = url('/images/profile_images/'.$file_name);

        // creating csv header
        $csv_header = ['Tracking #','Status','Reason','User','Domain','Created At'];

        // open or create file
        $file = fopen($path, 'a');

        // add header file on new file
        if($request->file_name == null)
        {
            fputcsv($file, $csv_header);
        }
        $manual_tracking_data = TrackingImageHistory::whereBetween(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),  [$date, $todate])
                                ->orderBy('created_at')
                                ->paginate($limit);
        foreach($manual_tracking_data as $tracking_data)
        {
            $created_at = '';
            if ($tracking_data->created_at) {
                $created_at = new \DateTime($tracking_data->created_at, new \DateTimeZone('UTC'));
                $created_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                $created_at =$created_at->format('Y-m-d H:i:s');
            }

            if ($tracking_data->domain == 'finance')
            {
                if (isset($tracking_data->FinanceUser)) {
                    $userName = $tracking_data->FinanceUser->full_name;
                } else {
                    $userName = '';
                }
            }
            else
            {
                if (isset($tracking_data->user)) {
                    $userName = $tracking_data->user->full_name;
                } else {
                    $userName = '';
                }
            }

            if (isset($tracking_data->status_id))
            {
                $current_status = $tracking_data->status_id;
                if ($current_status == 13) {
                    $statusName = "At hub Processing";
                } else {
                    $statusName = self::$status[$current_status];
                }
            }
            else
            {
                $statusName = '';
            }
       

            $csv_row = [
                $tracking_data->tracking_id,
                $statusName,
               isset($tracking_data->reason) ? $tracking_data->reason->title : '',
                $userName,
                $tracking_data->domain,
                $created_at
            ];
           
                    // putting csv file data
                    fputcsv($file,$csv_row);
        }

            

            $metaData['total_records'] = $manual_tracking_data->total();

            fclose($file);

            return RestAPI::setPagination($manual_tracking_data)->response([],200,'',$metaData);
    }

    


}
