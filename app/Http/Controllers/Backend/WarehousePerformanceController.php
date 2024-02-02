<?php

namespace App\Http\Controllers\Backend;

use App\Classes\RestAPI;
use App\CTCEntry;
use App\CtcVendor;
use App\FinanceVendorCity;
use App\AmazonEnteries;
use App\FinanceVendorCityDetail;
use App\Http\Traits\BasicModelFunctions;
use App\JoeyRouteLocations;
use App\JoeyRoutes;
use App\Sprint;
use App\SprintTaskHistory;
use App\Task;
use App\WarehouseJoeysCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class WarehousePerformanceController extends BackendController
{
    use BasicModelFunctions;

    public function getWarehousePerformance()
    {
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        return backend_view('warehouse_performance.index', compact('hubs'));
    }

    public function getWarehousePerformanceData(Request $request)
    {
        $record = [];
        $metaData = $request->all();
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        $hub_id = $metaData['hub-id'];
        $month = $metaData['month-id'];
        $metaData['current_page'] = ($metaData['current_page'] > 1) ? $metaData['current_page'] : 1;
        if (!empty($month) && !empty($hub_id)) {
            $metaData['total_pages'] = ($metaData['current_page'] > 1) ? $metaData['total_pages'] : cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
            $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
            $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

            $current_page = $metaData['current_page'];
            $j = $current_page;
            if ($current_page < 10) {
                $j = '0' . $current_page;
            }
            $date = date("Y-" . $month . '-' . $j);
            #InBound Team
            $montreal_packages = [];
            $ottawa_packages = [];
            $ctc_packages = [];
            $montreal_route = [];
            $ottawa_route = [];
            $ctc_route = [];
            $montreal_damage = 0;
            $ottawa_damage = 0;
            $ctc_damage = 0;
            $montreal_not_receive = 0;
            $ottawa_not_receive = 0;
            $ctc_not_receive = 0;
            $montreal_mis_order = 0;
            $ottawa_mis_order = 0;
            $ctc_mis_order = 0;

            #OutBound Team
            $montreal_picked_order = [];
            $ottawa_picked_order = [];
            $ctc_picked_order = [];

            #Closing Team
            $montreal_return_order = [];
            $ottawa_return_order = [];
            $ctc_return_order = [];
            $montreal_return_scan = [];
            $ottawa_return_scan = [];
            $ctc_return_scan = [];

            #Overall


            $number_of_sorters = WarehouseJoeysCount::where('hub_id', $hub_id)->where('date', $date)->select('sorter_counts')->first();
            // $system_route = JoeyRoutes::where(\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto')"), 'like', $date . "%")->count();


            if (in_array('477260', $vendors)) {
                $montreal_date = date('Y-m-d', strtotime($date . ' -1 days'));
                $sprintIds = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $montreal_date . "%")
                    ->where('creator_id', '477260')->pluck('sprint_id')->toArray();
                $montreal_packages = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 133)->groupBy('sprint_id')->pluck('id')->toArray();
                $montreal_picked_order = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 121)->groupBy('sprint_id')->pluck('id')->toArray();
                $montreal_return_order = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->whereIn('status_id', $this->getStatusCodes('return'))->groupBy('sprint_id')->pluck('id')->toArray();
                $montreal_return_scan = AmazonEnteries::whereIn('sprint_id', $sprintIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->pluck('id')->toArray();


                $taskIds = Task::whereIn('sprint_id', $sprintIds)->pluck('id')->toArray();
                $montreal_route = JoeyRouteLocations::whereIn('task_id', $taskIds)->groupBy('route_id')->pluck('route_id')->toArray();

                $montreal_damage = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $montreal_date . "%")
                    ->where('creator_id', '477260')->where('task_status_id', 105)->count();
                $montreal_not_receive = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $montreal_date . "%")
                    ->where('creator_id', '477260')->where('task_status_id', 61)->count();
                $montreal_mis_order = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $montreal_date . "%")
                    ->where('creator_id', '477260')->where('task_status_id', 140)->count();
            }
            if (in_array('477282', $vendors)) {
                $ottawa_date = date('Y-m-d', strtotime($date . ' -1 days'));
                $sprintIds = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $ottawa_date . "%")
                    ->where('creator_id', '477282')->pluck('sprint_id')->toArray();
                $ottawa_packages = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 133)->groupBy('sprint_id')->pluck('id')->toArray();
                $ottawa_picked_order = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 121)->groupBy('sprint_id')->pluck('id')->toArray();
                $ottawa_return_order = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->whereIn('status_id', $this->getStatusCodes('return'))->groupBy('sprint_id')->pluck('id')->toArray();
                $ottawa_return_scan = AmazonEnteries::whereIn('sprint_id', $sprintIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->pluck('id')->toArray();


                $taskIds = Task::whereIn('sprint_id', $sprintIds)->pluck('id')->toArray();
                $ottawa_route = JoeyRouteLocations::whereIn('task_id', $taskIds)->groupBy('route_id')->pluck('route_id')->toArray();

                $ottawa_damage = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $ottawa_date . "%")
                    ->where('creator_id', '477282')->where('task_status_id', 105)->count();
                $ottawa_not_receive = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $ottawa_date . "%")
                    ->where('creator_id', '477282')->where('task_status_id', 61)->count();
                $ottawa_mis_order = AmazonEnteries::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $ottawa_date . "%")
                    ->where('creator_id', '477282')->where('task_status_id', 140)->count();
            }
            if (in_array($ctcVendorIds, $vendors)) {
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);
                $sprintIds = CTCEntry::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->pluck('sprint_id')->toArray();
                $ctc_packages = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 133)->groupBy('sprint_id')->pluck('id')->toArray();
                $ctc_picked_order = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->where('status_id', 121)->groupBy('sprint_id')->pluck('id')->toArray();
                $ctc_return_order = SprintTaskHistory::whereIn('sprint_id', $sprintIds)->whereIn('status_id', $this->getStatusCodes('return'))->groupBy('sprint_id')->pluck('id')->toArray();
                $ctc_return_scan = AmazonEnteries::whereIn('sprint_id', $sprintIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->pluck('id')->toArray();

                $taskIds = Task::whereIn('sprint_id', $sprintIds)->pluck('id')->toArray();
                $ctc_route = JoeyRouteLocations::whereIn('task_id', $taskIds)->groupBy('route_id')->pluck('route_id')->toArray();

                $ctc_damage = CTCEntry::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->where('task_status_id', 105)->count();
                $ctc_not_receive = CTCEntry::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->where('task_status_id', 61)->count();
                $ctc_mis_order = CTCEntry::where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->where('task_status_id', 140)->count();
            }
            #Inbound Team
            $total_packages = count($montreal_packages) + count($ottawa_packages) + count($ctc_packages);
            $system_route = count($montreal_route) + count($ottawa_route) + count($ctc_route);
            $total_damaged_packages = $montreal_damage + $ottawa_damage + $ctc_damage;
            $not_received = $montreal_not_receive + $ottawa_not_receive + $ctc_not_receive;
            $total_mis_sort = $montreal_mis_order + $ottawa_mis_order + $ctc_mis_order;

            #Outbound Team
            $total_picked_order = count($montreal_picked_order) + count($ottawa_picked_order) + count($ctc_picked_order);

            #Closing Team
            $total_return_order = count($montreal_return_order) + count($ottawa_return_order) + count($ctc_return_order);
            $total_return_scan = count($montreal_return_scan) + count($ottawa_return_scan) + count($ctc_return_scan);

            #Overall


            $record['date'] = $date;
            #In Bound Team
            $record['total_packages'] = $total_packages;
            $record['number_of_sorters'] = $number_of_sorters ? $number_of_sorters->sorter_counts : 0;
            $record['total_system_routes'] = $system_route;
            $record['inbound_total_manual_routes'] = 0;
            $record['total_damaged_packages'] = $total_damaged_packages;
            $record['not_received'] = $not_received;
            $record['total_mis_sort'] = $total_mis_sort;
            $mis_ratio = 0;
            if ($total_packages >= 1) {
                $mis_ratio = round(($total_mis_sort / $total_packages) * 100, 2) ;
            }
            $record['total_mis_ratio'] = $mis_ratio. '%';

            #Out Bound Team
            $record['total_picked_order'] = $total_picked_order;
            $record['total_dispensed_routes'] = 0;
            $record['outbound_total_manual_routes'] = 0;
            $record['missing_stolen_packages'] = 0;
            $lost_packages = 0;
            if ($total_packages >= 1) {
                $lost_packages = round(($record['missing_stolen_packages'] / $total_packages) * 100) ;
            }
            $record['lost_packages'] = $lost_packages. '%';

            #Closing Team
            $record['total_same_day_returns'] = $total_return_order;
            $record['total_return_scan'] = $total_return_scan;
            $record['total_not_return_scan'] = $total_return_order - $total_return_scan;
            $record['total_completed_deliveries_before_9pm'] = 0;
            $record['total_completed_deliveries_after_9pm'] = 0;

            #OverAll
            $record['overall_total_manual_routes'] = 0;
            $dispencing_accuracy = 0;
            if ($total_packages >= 1) {
                $dispencing_accuracy = round((($record['missing_stolen_packages'] + $record['total_mis_sort'])/$total_packages) * 100, 2) ;
            }
            $record['dispencing_accuracy'] = $dispencing_accuracy. '%';
            $dispencing_accuracy_2 = 0;
            if ($total_packages >= 1) {
                $dispencing_accuracy_2 = round(100 - ((($record['missing_stolen_packages'] + $record['total_mis_sort'])/$total_packages) * 100), 2) ;
            }
            $record['dispencing_accuracy_2'] =$dispencing_accuracy_2. '%';
            $record['otd'] = round(0, 2) . '%';

        }

        return RestAPI::response($record, 200, '', $metaData);
    }


}
