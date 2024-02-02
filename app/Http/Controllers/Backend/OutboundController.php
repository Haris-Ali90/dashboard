<?php

namespace App\Http\Controllers\Backend;

use App\Joey;
use App\Task;
use App\User;
use App\Sprint;
use App\Manager;
use App\Setting;
use App\CTCEntry;
use App\CtcVendor;
use Carbon\Carbon;
use App\JoeyRoutes;
use App\BrookerJoey;
use App\BrookerUser;
use App\MerchantIds;
use App\AmazonEnteries;
use App\FinanceVendorCity;
use App\SprintTaskHistory;
use App\JoeyRouteLocations;
use App\WarehouseJoeysCount;
use Illuminate\Http\Request;
use App\TrackingImageHistory;
use App\FinanceVendorCityDetail;
use App\CustomerRoutingTrackingId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\BasicModelFunctions;
use DateTime;
use DateTimeZone;
class OutboundController extends BackendController
{
    use BasicModelFunctions;

    public function getOutbound(Request $request)
    {

        $input = $request->all();
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $range_from_date = isset($input['datepicker1']) ? $input['datepicker1'] : date('Y-m-d');
        $range_to_date = isset($input['datepicker2']) ? $input['datepicker2'] : date('Y-m-d');
        $current = date('Y-m-d');
        $all_dates = [];
        $start_date = $range_from_date;
        $end_date = $range_to_date;

        /*
        if ($date_filter == 'today') {
            $all_dates = [$current];
        } elseif ($date_filter == 'yesterday') {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-1 day', strtotime($current))));
            $range_to_date = new Carbon($current);
            while ($range_from_date->lte($range_to_date)) {
                $all_dates[] = $range_from_date->toDateString();
                $range_from_date->addDay();
            }
        } elseif ($date_filter == 'last-week') {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 day', strtotime($current))));
            $range_to_date = new Carbon($current);
            while ($range_from_date->lte($range_to_date)) {
                $all_dates[] = $range_from_date->toDateString();
                $range_from_date->addDay();
            }
        } else {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-14 day', strtotime($current))));
            $range_to_date = new Carbon($current);
            while ($range_from_date->lte($range_to_date)) {
                $all_dates[] = $range_from_date->toDateString();
                $range_from_date->addDay();
            }
        }
        */
       
        $range_from_date = new Carbon($range_from_date);
        $range_to_date = new Carbon($range_to_date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }

        $managers=Manager::where('deleted_at',null)->get();
        return backend_view('statistics.outbound', compact('hubs', 'hub_id', 'all_dates','managers','start_date','end_date'));
    }


    public function getOutboundData(Request $request)
    {
//        $input = $request->all();
//        $hub_id = $input['hub_id'];
//        $date = $input['date_filter'];
//        $hub_name = FinanceVendorCity::where('id', $hub_id)->first();
//        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
//        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
//        $sprint = new Sprint();
//        $warehouse_data = [];
//        #Total Picked Order
//        $montreal_picked_order = 0;
//        $ottawa_picked_order = 0;
//        $ctc_picked_order = 0;
//
//        #Total Mis Sort
//        $montreal_mis_order = 0;
//        $ottawa_mis_order = 0;
//        $ctc_mis_order = 0;
//
//        #Total Route
//        $montreal_route = [];
//        $ottawa_route = [];
//        $ctc_route = [];
        $date = date('Y-m-d');
        $warehouse_data[0] = [
            'date' => $prev_date = date('F d, Y', strtotime($date .' -1 day')),
            'date_id' => $prev_date = date('Y-m-d', strtotime($date .' -1 day')),
            'dispensing_start_time' => date('H:i A'),
            'dispensing_end_time' => date('H:i A'),
            'total_picked_order' => 3570,
            'total_mis_order' => 4,
            'dispensed_route' => 2,
            'total_route' => 60,
            'total_normal_route' => 55,
            'total_custom_route' => 4,
            'total_big_box_route' => 1,
            'hub_name' => '',
            'manager_on_duty' => '',
            'week' => '4th week',
            'day' => 'Day 26'
        ];

//        $firstOfMonth = date("Y", strtotime($date)) . '-' . date("m", strtotime($date)) . '-01';
//
//        $number_of_sorters = WarehouseJoeysCount::where('hub_id', $hub_id)->where('date', $date)->first();
//        if ($number_of_sorters) {
//            $warehouse_data[0]['dispensing_start_time'] = isset($number_of_sorters->dispensing_start_time) ? date('H:i A', strtotime($number_of_sorters->dispensing_start_time)) : null;
//            $warehouse_data[0]['dispensing_end_time'] = isset($number_of_sorters->dispensing_end_time) ? date('H:i A', strtotime($number_of_sorters->dispensing_end_time)) : null;
//            // $warehouse_data[0]['manager_on_duty'] = isset($number_of_sorters->manager_on_duty) ? $number_of_sorters->manager_on_duty : '';
//            $warehouse_data[0]['dispensed_route'] = isset($number_of_sorters->dispensed_route) ? $number_of_sorters->dispensed_route : 0;
//
//            $warehouse_data[0]['manager_on_duty'] = isset($number_of_sorters->Manager) ? $number_of_sorters->Manager->name : '';
//            $warehouse_data[0]['manager_on_duty_id'] = ($number_of_sorters->manager_on_duty==null) ? '':$number_of_sorters->manager_on_duty;
//
//        }
//        $warehouse_data[0]['hub_name'] = $hub_name->city_name;
//        if (in_array('477260', $vendors)) {
//            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
//
//            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//            $sprintIds = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)
//                ->where('creator_id', '477260')->pluck('sprint_id')->toArray();
//
//            #Total Picked Order
//            $montreal_picked_order = AmazonEnteries::whereIn('sprint_id', $sprintIds)->whereNotNull('picked_up_at')->count();
//              //  SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 121)->groupBy('sprint_id')->pluck('id')->toArray();
//
//            #Total Mis Sort
//            $montreal_mis_order = AmazonEnteries::whereIn('sprint_id', $sprintIds)->where('task_status_id', 140)->count();
//
//            #Total Route
//            $montreal_route = JoeyRoutes::where('hub',16)->where('date', 'like', $date . "%")->pluck('id')->toArray();
//            //$montreal_route = AmazonEnteries::whereIn('sprint_id', $sprintIds)->groupBy('route_id')->pluck('route_id')->toArray();
//        }
//        if (in_array('477282', $vendors)) {
//            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
//
//            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//            $sprintIds = AmazonEnteries::where('created_at','>',$start)->where('created_at','<',$end)
//                ->where('creator_id', '477282')->pluck('sprint_id')->toArray();
//
//            #Total Picked Order
//            $ottawa_picked_order = AmazonEnteries::whereIn('sprint_id', $sprintIds)->whereNotNull('picked_up_at')->count();
//                //SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 121)->groupBy('sprint_id')->pluck('id')->toArray();
//
//            #Total Mis Sort
//            $ottawa_mis_order = AmazonEnteries::whereIn('sprint_id', $sprintIds)->where('task_status_id', 140)->count();
//
//            #Total Route
//             $ottawa_route = JoeyRoutes::where('hub',19)->where('date', 'like', $date . "%")->pluck('id')->toArray();
//            // $ottawa_route = AmazonEnteries::whereIn('sprint_id', $sprintIds)->groupBy('route_id')->pluck('route_id')->toArray();
//        }
//        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
//            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
//
//            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//            $sprintIds = CTCEntry::whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
//                ->pluck('sprint_id')->toArray();
//
//            #Total Picked Order
//            $ctc_picked_order =  CTCEntry::whereIn('sprint_id', $sprintIds)->whereNotNull('picked_up_at')->count();
//                //SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 121)->groupBy('sprint_id')->pluck('id')->toArray();
//
//            #Total Mis Sort
//            $ctc_mis_order = CTCEntry::whereIn('sprint_id', $sprintIds)->where('task_status_id', 140)->count();
//
//            #Total Route
//           $ctc_route = JoeyRoutes::where('hub',17)->where('date', 'like', $date . "%")->pluck('id')->toArray();
//            //$ctc_route = CTCEntry::whereIn('sprint_id', $sprintIds)->groupBy('route_id')->pluck('route_id')->toArray();
//        }
//
//        $warehouse_data[0]['total_mis_order'] = $montreal_mis_order + $ottawa_mis_order + $ctc_mis_order;
//        $warehouse_data[0]['total_picked_order'] = $montreal_picked_order + $ottawa_picked_order +$ctc_picked_order;
//
//        $total_route = 0;
//        $normal_route = 0;
//        $custom_route = 0;
//        $big_box_route = 0;
//
//        $routeIds = array_merge($montreal_route, $ottawa_route, $ctc_route);
//        if (!empty($routeIds)) {
//            $route_data = JoeyRoutes::whereIn('id',$routeIds)->where('date', 'like', $date . "%")->whereNull('deleted_at')->get();
//            foreach ($route_data as $route) {
//                $route_location_check = DB::table('joey_route_locations')->where('route_id', $route->id)->whereNull('deleted_at')->first();
//                if ($route_location_check) {
//                    if ($route->zone != null) {
//                        $is_custom_check = \DB::table("zones_routing")->where('id', $route->zone)->whereNull('is_custom_routing')->first();
//                        if ($is_custom_check) {
//                            $normal_route++;
//                        } else {
//                            $route_location = DB::table('joey_route_locations')->where('route_id', $route->id)->first();
//                            if ($route_location) {
//                                $tracking = DB::table('merchantids')->where('task_id', $route_location->task_id)->first();
//                                if ($tracking) {
//                                    $custom_route_data = DB::table('custom_routing_tracking_id')->where('tracking_id', $tracking->tracking_id)->first();
//                                    if ($custom_route_data) {
//                                        if ($custom_route_data->is_big_box == 1) {
//                                            $big_box_route++;
//                                        } else {
//                                            $custom_route++;
//                                        }
//                                    } else {
//                                        $custom_route++;
//                                    }
//                                } else {
//                                    $custom_route++;
//                                }
//                            } else {
//                                $custom_route++;
//                            }
//                        }
//                    } else {
//                        $route_location = DB::table('joey_route_locations')->where('route_id', $route->id)->first();
//                        if ($route_location) {
//                            $tracking = DB::table('merchantids')->where('task_id', $route_location->task_id)->first();
//                            if ($tracking) {
//                                $custom_route_data = DB::table('custom_routing_tracking_id')->where('tracking_id', $tracking->tracking_id)->first();
//                                if ($custom_route_data) {
//                                    if ($custom_route_data->is_big_box == 1) {
//                                        $big_box_route++;
//                                    } else {
//                                        $custom_route++;
//                                    }
//                                } else {
//                                    $custom_route++;
//                                }
//                            } else {
//                                $custom_route++;
//                            }
//                        } else {
//                            $custom_route++;
//                        }
//                    }
//                    $total_route++;
//                }
//            }
//        }
//        $warehouse_data[0]['total_route'] = $total_route;
//        $warehouse_data[0]['total_normal_route'] = $normal_route;
//        $warehouse_data[0]['total_custom_route'] = $custom_route;
//        $warehouse_data[0]['total_big_box_route'] = $big_box_route;
//        $warehouse_data[0]['date'] = date('F d, Y', strtotime($date));
//        $warehouse_data[0]['day'] = 'Day ' . date('d', strtotime($date));
//        $week_count = intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)));
//        $week = '';
//        if ($week == 1) {
//            $week = '1st Week';
//        } elseif ($week == 2) {
//            $week = '2nd Week';
//        } elseif ($week == 3) {
//            $week = '3rd Week';
//        } elseif ($week == 4) {
//            $week = '4th Week';
//        } else {
//            $week = '';
//        }
//        $warehouse_data[0]['week'] = $week;
//        $warehouse_data[0]['date_id'] = $date;

        // dd($warehouse_data);
        return $warehouse_data;
    }

    public function outboundDispensingTime(Request $request)
    {
        $input = $request->all();

        $time = date('H:i:s');
        $record = [];
        $warehouseSorters = WarehouseJoeysCount::where('hub_id', $input['hubId'])->where('date', $input['dateTime'])->first();
        if ($warehouseSorters) {
            if ($warehouseSorters->dispensing_start_time) {
                $warehouseSorters->dispensing_end_time = $time;
                $warehouseSorters->save();
                $record['state'] = 2;
                $record['time'] = date('H:i A', strtotime($time));
            } else {
                $warehouseSorters->dispensing_start_time = $time;
                $warehouseSorters->save();
                $record['state'] = 1;
                $record['time'] = date('H:i A', strtotime($time));
            }
        } else {
            $recordCreate = [
                'hub_id' => $input['hubId'],
                'date' => $input['dateTime'],
                'dispensing_start_time' => $time
            ];
            WarehouseJoeysCount::create($recordCreate);
            $record['state'] = 1;
            $record['time'] = date('H:i A', strtotime($time));
        }
        return $record;
    }
    public function wareHouseSorterUpdate(Request $request)
    {
        $input = $request->all();
        // print_r($input);die;

        if($input['dispensed_route']==''){
            $input['dispensed_route']=0;    
        }
       
     
        $warehouseSorters = WarehouseJoeysCount::where('hub_id', $input['hub_id'])->where('date', $input['date'])->first();
        if ($warehouseSorters) {
          
            $warehouseSorters->dispensed_route = $input['dispensed_route'];
            if($input['manager_on_duty']!=''){
                $warehouseSorters->manager_on_duty = $input['manager_on_duty'];
            }
            $warehouseSorters->save();
            // WarehouseJoeysCount::where('id', $warehouseSorters->id)->update($recordUpdate);
        }else{
            $recordCreate = [
                'hub_id' => $input['hub_id'],
                'date' => $input['date'],
                'dispensed_route' => $input['dispensed_route']
            ];
            if($input['manager_on_duty']!=''){
                $recordCreate['manager_on_duty']=$input['manager_on_duty'];
            }
            $warehouseSorters=WarehouseJoeysCount::create($recordCreate);
            
        }
        // print_r($warehouseSorters);die;
        $return=[
            'dispensed_route' => $input['dispensed_route'],
            'manager'=>($warehouseSorters->Manager!=null) ? $warehouseSorters->Manager->name : ""
        ];
        return $return;
      


    }
}
