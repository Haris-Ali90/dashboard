<?php

namespace App\Http\Controllers\Backend;

use App\Sprint;
use App\Task;
use App\Vendor;
use Config;

use Illuminate\Http\Request;


use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\UserEntities;

class ReportingController extends BackendController
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
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow');

    public function getReporting(Request $request)
    {


        $vendors = Vendor::whereNull('vendors.deleted_at')->selectRaw('id, CONCAT(`first_name`, " ", `last_name`) as name')
            ->get()->pluck('name', 'id')->toArray();

        return backend_view('reporting.orders_dashboard',compact('vendors'));
    }

    public function reportingData(Datatables $datatables, Request $request)
    {

        $vendors =$request->get('vendors');
    /*    dd($vendors);*/
        $from_date = !empty($request->get('fromdatepicker')) ? $request->get('fromdatepicker') : date("Y-m-d");
        $to_date = !empty($request->get('todatepicker')) ? $request->get('todatepicker') : date("Y-m-d");
        $from_date = $from_date." 00:00:00";
        $to_date = $to_date." 23:59:59";
        $sprintid = Sprint::where(['creator_id' => $vendors])->pluck('id');

        $query= Sprint::join('sprint__tasks','sprint__tasks.sprint_id','=','sprint__sprints.id')
                    ->leftjoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
                    ->leftjoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->whereIn('sprint__sprints.id' , $sprintid)
            ->whereBetween('sprint__sprints.created_at', [$from_date, $to_date])
            ->whereNull('sprint__sprints.deleted_at')
            ->where(['sprint__tasks.type' => 'dropoff'])
            //->whereNull('sprint__tasks.deleted_at')
            //->whereNotNull('merchantids.tracking_id')
            ->select('sprint__sprints.id','sprint__sprints.in_hub_route','merchantids.tracking_id','sprint__sprints.status_id as sprint_status','sprint__tasks.status_id as task_status','sprint__sprints.created_at','sprint__sprints.updated_at','joey_route_locations.route_id');
                /*     $query=Sprint::join('sprint__tasks','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->join('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->leftjoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->where(['sprint__sprints.creator_id' => $vendors])
            ->whereBetween('sprint__sprints.created_at', [$from_date, $to_date])
            ->where(['sprint__tasks.type' => 'dropoff'])
            ->whereNull('sprint__tasks.deleted_at')
            ->select('sprint__sprints.*','sprint__tasks.status_id as task_id','sprint__tasks.type as type','joey_route_locations.route_id as route_id','merchantids.tracking_id as tracking_id')
        ;*/

        return $datatables->eloquent($query)
        ->setRowId(static function ($query) {
            return $query->id;
        })->addColumn('sprint_status', static function ($query) {
                return self::$status[$query->sprint_status];
            })
            ->addColumn('task_status', static function ($query) {
                return self::$status[$query->task_status];
            })
            ->editColumn('tracking_id', static function ($query) {
                if ($query->tracking_id )
                    return $query->tracking_id;
                else
                    return '';

            })
            ->editColumn('route_id', static function ($query) {
                if ($query->in_hub_route == 1)
                return 'yes in route ( '.$query->route_id.')';
                else
                    return 'no in route   ';
            })
            ->make(true);
    }





    public function reportingExcel($vendor,$fromdate,$todate)
    {
         $vendors =$vendor;
        $from_date = $fromdate;
        $to_date = $todate;
        $from_date = $from_date." 00:00:00";
        $to_date = $to_date." 23:59:59";
        $sprintid = Sprint::where(['creator_id' => $vendors])->pluck('id')->toArray();
        $reportingData=Sprint::join('sprint__tasks','sprint__tasks.sprint_id','=','sprint__sprints.id')
            ->leftjoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftjoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->whereIn('sprint__sprints.id' , $sprintid)
            ->whereBetween('sprint__sprints.created_at', [$from_date, $to_date])
            ->whereNull('sprint__sprints.deleted_at')
            ->where(['sprint__tasks.type' => 'dropoff'])

            ->get(['sprint__sprints.id','sprint__sprints.in_hub_route','merchantids.tracking_id','sprint__sprints.status_id as sprint_status','sprint__tasks.status_id as task_status','sprint__sprints.created_at','sprint__sprints.updated_at','joey_route_locations.route_id']);
           ;

        $reporting_array[] = array('sprint_id', 'tracking_id', 'created_at', 'updated_at', 'status_id', 'task_id', 'route_id');
        foreach ($reportingData as $reporting) {
            $reporting_array[] = array(
                'sprint_id' => strval($reporting->id),
                'tracking_id' => $reporting->tracking_id,
                'created_at' => $reporting->created_at,
                'updated_at' => $reporting->updated_at,
                'status_id' =>self::$status[$reporting->sprint_status],
                'task_id' => self::$status[$reporting->task_status],
                'route_id' => ($reporting->in_hub_route == 1)?'yes in route (' . $reporting->route_id . ')':'not  in route ',

            );
        }


        Excel::create('Reporting Data '.$from_date.'/'.$to_date, function ($excel) use ($reporting_array) {
            $excel->setTitle('Reporting Data');
            $excel->sheet('Reporting Data', function ($sheet) use ($reporting_array) {
                $sheet->fromArray($reporting_array, null, 'A1', false, false);
            });
        })->download('csv');
    }

}
