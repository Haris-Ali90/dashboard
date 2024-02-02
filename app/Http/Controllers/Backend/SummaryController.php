<?php

namespace App\Http\Controllers\Backend;

use App\BrookerJoey;
use App\BrookerUser;
use App\CTCEntry;
use App\CtcVendor;
use App\CustomerRoutingTrackingId;
use App\FinanceVendorCity;
use App\AmazonEnteries;
use App\FinanceVendorCityDetail;
use App\Http\Traits\BasicModelFunctions;
use App\Joey;
use App\JoeyRouteLocations;
use App\JoeyRoutes;
use App\MerchantIds;
use App\Setting;
use App\Sprint;
use App\SprintTaskHistory;
use App\Task;
use App\TrackingImageHistory;
use App\User;
use App\WarehouseJoeysCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
class SummaryController extends BackendController
{
    use BasicModelFunctions;

    public function getSummary(Request $request)
    {
        $input = $request->all();
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;

        $range_from_date = isset($input['datepicker1']) ? $input['datepicker1'] : date('Y-m-d');
        $range_to_date = isset($input['datepicker2']) ? $input['datepicker2'] : date('Y-m-d');
        $start_date = $range_from_date;
        $end_date = $range_to_date;
        $current = date('Y-m-d');
       /* $all_dates = [];
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
        }*/
        $range_from_date = new Carbon($range_from_date);
        $range_to_date = new Carbon($range_to_date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }


        return backend_view('statistics.summary', compact('hubs', 'hub_id', 'all_dates','start_date','end_date'));
    }

    public function getSummaryData(Request $request)
    {
        $input = $request->all();

        $hub_id = $input['hub_id'];
        $date = date('Y-m-d');
        $hub_name = FinanceVendorCity::where('id', $hub_id)->first();
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $firstOfMonth = date("Y", strtotime($date)) . '-' . date("m", strtotime($date)) . '-01';
        $warehouse_data[0]['setup_start_time'] = '11:00:00';
        $warehouse_data[0]['setup_end_time'] = '09:00:00';
        $warehouse_data[0]['start_sorting_time'] = '05:00:00';
        $warehouse_data[0]['end_sorting_time'] = '07:00:00';
        $warehouse_data[0]['internal_sorter_count'] = 110;
        $warehouse_data[0]['brooker_sorter_count'] = 42;
        $warehouse_data[0]['dispensing_start_time'] = '11:30:00';
        $warehouse_data[0]['dispensing_end_time'] = '06:00:00';
        $warehouse_data[0]['dispensed_route'] = 2;
        $warehouse_data[0]['manager_on_duty'] = 2;
        $warehouse_data[0]['total_packages'] = 110;
        $warehouse_data[0]['total_damaged_packages'] = 3;
        $warehouse_data[0]['total_system_routes'] = 2;
        $warehouse_data[0]['total_not_receive'] = 15;
        $warehouse_data[0]['total_mis_order'] = 4;
        $warehouse_data[0]['total_picked_order'] = 75;
        $warehouse_data[0]['total_same_day_returns'] = 40;
        $warehouse_data[0]['total_return_scan'] = 3;
        $warehouse_data[0]['total_not_return_scan'] = 3 - 2;
        $warehouse_data[0]['total_completed_deliveries_before_9pm'] = 110 - 35;
        $warehouse_data[0]['total_completed_deliveries_after_9pm'] = 35;
        $warehouse_data[0]['missing_stolen_packages'] = 0;
        $warehouse_data[0]['dispencing_accuracy'] = 15 . '%';
        $warehouse_data[0]['dispencing_accuracy_2'] = 7 . '%';
        $warehouse_data[0]['otd'] = round(75, 2) . '%';
        $warehouse_data[0]['total_mis_ratio'] = 11 . '%';
        $warehouse_data[0]['lost_packages'] = 2.4 . '%';
        $warehouse_data[0]['overall_total_manual_routes'] = 1 + 0;
        $warehouse_data[0]['total_route'] = 2;
        $warehouse_data[0]['total_normal_route'] = 1;
        $warehouse_data[0]['total_custom_route'] = 1;
        $warehouse_data[0]['total_big_box_route'] = 0;
        $warehouse_data[0]['date'] = date('F d, Y', strtotime($date));
        $warehouse_data[0]['day'] = 'Day ' . date('d', strtotime($date));
        $week_count = intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)));
        $week = '';
        if ($week_count == 1) {
            $week = '1st Week';
        } elseif ($week_count == 2) {
            $week = '2nd Week';
        } elseif ($week_count == 3) {
            $week = '3rd Week';
        } elseif ($week_count == 4) {
            $week = '4th Week';
        } else {
            $week = '';
        }
        $warehouse_data[0]['week'] = $week;
        $warehouse_data[0]['hub_name'] = $hub_name->city_name;




        return $warehouse_data;
    }
}
