<?php

namespace App\Http\Controllers\Backend;

use App\AlertSystem;
use App\BoradlessDashboard;
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
use App\TrackingImageHistory;
use App\User;
use App\Vendor;
use App\WarehouseJoeysCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
class VendorManagementController extends BackendController
{
    use BasicModelFunctions;

    public function getStatistics(Request $request)
    {
        $input = $request->all();
        $currentVendorId = 477661;
        $vendor_id = isset($input['vendor_id']) ? $input['vendor_id']: $currentVendorId;
        $vendor_name = Vendor::where('id', $vendor_id)->first();
        return backend_view('vendor-management.index', compact('vendor_name','vendor_id') );
    }

    public function getDayOtd(Request $request)
    {
        $input = $request->all();
        $start_date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $end_date = $request->get('end_date') ? $request->get('end_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;

        $totalcount = 0;
        $totallates = 0;
        $totalearly = 0;


        $start_dt = new DateTime($start_date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($end_date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $trackingIds = DB::table('custom_routing_tracking_id')->where('valid_id',1)->where('vendor_id',477661)->where('created_at','>',$start)->where('created_at','<',$end)
            ->where('is_inbound', 1)->pluck('tracking_id');

        $inBoundcreatedAt = DB::table('custom_routing_tracking_id')->whereIn('tracking_id',$trackingIds)
            ->where('is_inbound', 1)->pluck('created_at');

        $query1 = BoradlessDashboard::whereIn('tracking_id',$trackingIds)->orderBy('created_at', 'DESC')->groupBy('tracking_id')
            ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")])->toArray();


        $sprintIds = DB::table('sprint_reattempts')->where('created_at','>',$start)->where('created_at','<',$end)->pluck('sprint_id');
        $reAttemptsCreatedAt = DB::table('sprint_reattempts')->whereIn('sprint_id',$sprintIds)->pluck('created_at');

        $query2 = BoradlessDashboard::where('creator_id',477661)->whereIn('sprint_id',$sprintIds)->orderBy('created_at', 'DESC')->groupBy('tracking_id')
            ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")])->toArray();

        $counts1 = $this->receivedAtHubQuery($query1, $inBoundcreatedAt);
        $counts2 = $this->receivedAtHubQuery($query2, $reAttemptsCreatedAt);

        $totallates = $counts1['totalLates']+$counts2['totalLates'];
        $totalearly = $counts1['totalEarly']+$counts2['totalEarly'];
        $totalcount = $counts1['totalCount']+$counts2['totalCount'];

        $totalcount = $totallates + $totalearly;

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }

        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(($totalearly / $totalcount) * 100, 2), 'ontime'=> $totalearly, 'offtime'=> $totallates ];


        return $odt_data_1;
    }

    public function getWeekOtd(Request $request)
    {
        $input = $request->all();

        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
//        $ctcVendorIds = BoradlessDashboard::pluck('creator_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;
        $totalearly=0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 day', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }

//        dd($all_dates);

        foreach ($all_dates as $range_date) {
//            if (in_array('477661', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


//                dd($start,$end);

                $query = BoradlessDashboard::where('creator_id', '477661')->where('created_at','>',$start)->where('created_at','<',$end)->whereNotNull('delivered_at')
                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                $totallates++;
                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at) && $record->delivered_at < $range_date . " 21:00:00") {
                                $totalearly++;
                            }
//                        }
                        $totalcount++;
                    }
                }
//            }


//            if (in_array('477282', $vendors)) {
//                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
//
//                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }

//            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
//                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
//                $ctc_ids = array_intersect($ctcVendorIds, $vendors);
//
//                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
//                $query = BoradlessDashboard::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }
        }
//        dd($totalcount,$totalearly,$totallates);

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }

         $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2) ,'ontime'=>  $totalcount - $totallates  , 'offtime'=> $totallates ];


        return $odt_data_1;
    }

    public function getMonthOtd(Request $request)
    {
        $input = $request->all();

        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
//        $ctcVendorIds = BoradlessDashboard::pluck('creator_id')->toArray();

        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;
        $totalearly = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-1 month', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
//            if (in_array('477661', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = BoradlessDashboard::where('creator_id', '477661')->where('created_at','>',$start)->where('created_at','<',$end)->whereNotNull('delivered_at')
                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at")]);
                if (!empty($query)) {

                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                $totallates++;
                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at) && $record->delivered_at < $range_date . " 21:00:00") {
                                $totalearly++;
                            }
//                        }
                        $totalcount++;
                    }

                }
//            }

//            if (in_array('477282', $vendors)) {
//                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
//
//                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }

//            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
//                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
//                $ctc_ids = array_intersect($ctcVendorIds, $vendors);
//
//                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
//                $query = BoradlessDashboard::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }
        }

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2) ,'ontime'=> $totalcount - $totallates  , 'offtime'=> $totallates ];

        return $odt_data_1;
    }

    public function getYearOtd(Request $request)
    {
        $input = $request->all();

        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
//        $ctcVendorIds = BoradlessDashboard::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;
        $totalearly = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 month', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
//            if (in_array('477661', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = BoradlessDashboard::where('creator_id', '477661')->where('created_at','>',$start)->where('created_at','<',$end)->whereNotNull('delivered_at')
                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at")]);
                if (!empty($query)) {

                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                $totallates++;
                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                $totalearly++;
                            }
//                        }
                        $totalcount++;
                    }

                }
//            }

//            if (in_array('477282', $vendors)) {
//                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
//
//                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }

//            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
//                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
//                $ctc_ids = array_intersect($ctcVendorIds, $vendors);
//
//                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
//                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }
        }
        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
         $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2) ,'ontime'=> $totalcount - $totallates  , 'offtime'=> $totallates ];


        return $odt_data_1;
    }

    public function getAllCounts(Request $request)
    {
        $input = $request->all();

        $start_date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $end_date = $request->get('end_date') ? $request->get('end_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();

        $boradlessDashboardCounts = [
            'total' => 0,
            'sorted' => 0,
            'pickup' => 0,
            'delivered_order' => 0,
            'return_orders' => 0,
            'hub_return_scan' => 0,
            'notscan' => 0,
            'reattempted' => 0,
            'completion_ratio' =>0
        ];

//        if (in_array('477661', $vendors)) {

//            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($start_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($end_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('boradless_dashboard')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477661])->where('is_custom_route', 0)->pluck('task_id');
            $boradlessDashboard = new BoradlessDashboard();
            $boradlessDashboardCounts = $boradlessDashboard->getBoradlessCountsForLoop($taskIds, $start, $end);
//        }


//        if (in_array('477282', $vendors)) {
//            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));
//
//            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//
//            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
//                ->where(['creator_id' => 477282])->where('is_custom_route', 0)->pluck('task_id');
//            $amazon = new AmazonEnteries();
//            $ottawa_count = $amazon->getAmazonCountsForLoop($taskIds, 'all');
//        }

//        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
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
//
//            $taskIds = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id',$ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->pluck('task_id');
//            $ctc = new CTCEntry();
//            $ctc_count = $ctc->getCtcCounts($taskIds, 'all');
//        }
        $counts['total'] = $boradlessDashboardCounts['total'];
        $counts['sorted'] = $boradlessDashboardCounts['sorted'];
        $counts['pickup'] = $boradlessDashboardCounts['pickup'];
        $counts['delivered_order'] = $boradlessDashboardCounts['delivered_order'];
        $counts['return_orders'] = $boradlessDashboardCounts['return_orders'];
        $counts['hub_return_scan'] = $boradlessDashboardCounts['hub_return_scan'];
        $counts['hub_not_return_scan'] = $counts['return_orders']-$counts['hub_return_scan'];
        $counts['notscan'] = $boradlessDashboardCounts['notscan'];
        $counts['pickup_from_store'] = $boradlessDashboardCounts['pickup_from_store'];
        $counts['reattempted'] = $boradlessDashboardCounts['reattempted'];
        $counts['received_at_hub'] = $boradlessDashboardCounts['received_at_hub'];

        if ($counts['pickup'] > 0) {
            $counts['completion_ratio'] = round(($counts['delivered_order'] / $counts['pickup']) * 100, 2).'%';
        }
        else
        {
            $counts['completion_ratio']  = 0.00.'%';
        }
      //  ($montreal_count['completion_ratio']+$ottawa_count['completion_ratio']+$ctc_count['completion_ratio']).'%';

        return $counts;
    }

    public function getInprogress(Request $request)
    {
        $input = $request->all();
        $start_date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $end_date = $request->get('end_date') ? $request->get('end_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
//        $ctcVendorIds = BoradlessDashboard::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();

        $getInProgressOrderCounts = [
            'remaining_sorted' => 0,
            'remaining_pickup' => 0,
            'remaining_route' => 0,
        ];

//        if (in_array('477661', $vendors)) {
//            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($start_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($end_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $taskIds = DB::table('boradless_dashboard')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477661])->where('is_custom_route', 0)->pluck('task_id');

            $boradlessDashboard = new BoradlessDashboard();
            $getInProgressOrderCounts = $boradlessDashboard->getInprogressOrders($taskIds, 'all');
//        }

//        if (in_array('477282', $vendors)) {
//            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));
//
//            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//
//            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
//                ->where(['creator_id' => 477282])->where('is_custom_route', 0)->pluck('task_id');
//            $amazon = new AmazonEnteries();
//            $ottawa_count = $amazon->getInprogressOrders($taskIds, 'all');
//        }

//        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
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
//
//            $taskIds = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id',$ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->pluck('task_id');
//            $ctc = new CTCEntry();
//            $ctc_count = $ctc->getInprogressOrders($taskIds, 'all');
//        }
        $counts['remaining_sorted'] = $getInProgressOrderCounts['remaining_sorted'];
        $counts['remaining_pickup'] = $getInProgressOrderCounts['remaining_pickup'];
        $counts['remaining_route'] = $getInProgressOrderCounts['remaining_route'];
        return $counts;
    }

    //view file function
    public function getOrders(Request $request)
    {
        $input = $request->all();
        $date =   $input['start_date'];
        $hub_id =  base64_decode($input['hub']);
        $type = base64_decode($input['type']);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $montreal =[];
        $ottawa = [];
        $ctc = [];
        $title = '';
        if ($type == 'return') {$title = 'Return Orders';}
        elseif ($type == 'hub_return'){$title = 'Hub Return Orders';}
            elseif($type == 'not_hub_return'){$title = 'Hub Not Return Orders';}
        elseif($type == 'custom'){$title = 'Custom Route Orders';}
        elseif($type == 'reattempted'){$title = 'Reattempted Orders';}
            else{$title = 'Not Scan';}
        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            if ($type == 'return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereIn('task_status_id', $sprint->getStatusCodes('return'))->get();
            }
            if ($type == 'hub_return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan'){
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereIn('task_status_id',[61])
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'reattempted'){
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereIn('task_status_id',[13])
                    ->whereNull('hub_return_scan')->get();
            }

            if ($type == 'custom'){
                $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->where('is_custom_route', 1)
                    ->whereNull('hub_return_scan')->get();
            }
        }

        if (in_array('477282', $vendors)) {
            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));


            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            if ($type == 'return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->where('is_custom_route', 0)->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->get();
            }
            if ($type == 'hub_return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->where('is_custom_route', 0)->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->where('is_custom_route', 0)->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan'){
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->where('is_custom_route', 0)->whereIn('task_status_id',[61])
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'reattempted'){
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->where('is_custom_route', 0)->whereIn('task_status_id',[13])
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'custom') {
                $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->where('is_custom_route', 1)
                    ->whereNull('hub_return_scan')->get();
            }
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);


            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            if ($type == 'return') {
                $ctc = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->get();
            }
            if ($type == 'hub_return') {
                $ctc = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $ctc = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan') {
                $ctc = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->whereIn('task_status_id', [61])->whereNull('hub_return_scan')->get();
            }
            if ($type == 'reattempted'){
                $ctc = DB::table('ctc_entries')->where('is_custom_route', 0)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->whereIn('task_status_id', [13])->whereNull('hub_return_scan')->get();
            }
            if ($type == 'custom') {
                $ctc = DB::table('ctc_entries')->where('is_custom_route', 1)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                   ->whereNull('hub_return_scan')->get();
            }
        }

        $result = array_merge( $montreal, $ottawa, $ctc );
        return backend_view('statistics.detail', compact('result','title'));
    }

    public function getFailedOrders(Request $request)
    {
        $input = $request->all();
        $date =   $input['start_date'];
        $hub_id =  base64_decode($input['hub']);
        $type = base64_decode($input['type']);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $montreal =[];
        $ottawa = [];
        $ctc = [];
        $title = '';
        if ($type == 'failed') {$title = 'Failed Orders';}
        elseif ($type == 'failed_create'){$title = 'Failed Orders Created';}
        else{$title = 'Failed Order Not Created';}

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $failedTrackingId = DB::table('xml_failed_orders')->join('mainfest_fields', 'mainfest_fields.trackingID', '=', 'xml_failed_orders.tracking_id')
                ->where('xml_failed_orders.created_at','>',$start)->where('xml_failed_orders.created_at','<',$end)->whereNotNull('mainfest_fields.trackingID')
                ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477260])
                ->pluck('trackingID');

            if ($type == 'failed') {
                $montreal = DB::table('mainfest_fields')->whereIn('mainfest_fields.trackingID',$failedTrackingId)
                    ->get(['trackingID AS tracking_num','consigneeAddressLine1 AS address','consigneeAddressName AS customer_name','customerOrderNumber AS merchant_order_number']);
            }
            if ($type == 'failed_create') {
                $merchnatTracking = MerchantIds::whereIn('tracking_id', $failedTrackingId)->pluck('tracking_id')->toArray();
                $montreal = DB::table('mainfest_fields')->whereIn('mainfest_fields.trackingID',$merchnatTracking)
                    ->get(['trackingID AS tracking_num','consigneeAddressLine1 AS address','consigneeAddressName AS customer_name','customerOrderNumber AS merchant_order_number']);
            }
            if ($type == 'failed_not_create') {
                $merchnatTracking = MerchantIds::whereIn('tracking_id', $failedTrackingId)->pluck('tracking_id')->toArray();
                $not_create_tracking  = array_diff($failedTrackingId, $merchnatTracking);
                $montreal = DB::table('mainfest_fields')->whereIn('mainfest_fields.trackingID',$not_create_tracking)
                    ->get(['trackingID AS tracking_num','consigneeAddressLine1 AS address','consigneeAddressName AS customer_name','customerOrderNumber AS merchant_order_number']);
            }

        }

        if (in_array('477282', $vendors)) {
            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $failedTrackingId = DB::table('xml_failed_orders')->join('mainfest_fields', 'mainfest_fields.trackingID', '=', 'xml_failed_orders.tracking_id')
                ->where('xml_failed_orders.created_at','>',$start)->where('xml_failed_orders.created_at','<',$end)
                ->whereNotNull('mainfest_fields.trackingID')
                ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477282])->pluck('trackingID');
            if ($type == 'failed') {
                $ottawa = DB::table('mainfest_fields')->whereIn('mainfest_fields.trackingID',$failedTrackingId)
                    ->get(['trackingID AS tracking_num','consigneeAddressLine1 AS address','consigneeAddressName AS customer_name','customerOrderNumber AS merchant_order_number']);
            }
            if ($type == 'failed_create') {
                $merchnatTracking = MerchantIds::whereIn('tracking_id', $failedTrackingId)->pluck('tracking_id')->toArray();
                $ottawa = DB::table('mainfest_fields')->whereIn('mainfest_fields.trackingID',$merchnatTracking)
                    ->get(['trackingID AS tracking_num','consigneeAddressLine1 AS address','consigneeAddressName AS customer_name','customerOrderNumber AS merchant_order_number']);
            }
            if ($type == 'failed_not_create') {
                $merchnatTracking = MerchantIds::whereIn('tracking_id', $failedTrackingId)->pluck('tracking_id')->toArray();
                $not_create_tracking  = array_diff($failedTrackingId, $merchnatTracking);
                $ottawa = DB::table('mainfest_fields')->whereIn('mainfest_fields.trackingID',$not_create_tracking)
                    ->get(['trackingID AS tracking_num','consigneeAddressLine1 AS address','consigneeAddressName AS customer_name','customerOrderNumber AS merchant_order_number']);
            }
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $failed_tracking_Ids = DB::table('ctc_failed_orders')->whereIn('vendor_id' ,$ctc_ids)
                ->where('ctc_failed_orders.created_at','>',$start)->where('ctc_failed_orders.created_at','<',$end)->pluck('tracking_num');

            if ($type == 'failed') {
                $ctc = DB::table('ctc_failed_orders')->whereIn('tracking_num',$failed_tracking_Ids)->get(['tracking_num','customer_name','address','merchant_order_number']);
            }
            if ($type == 'failed_create') {
                $merchnatTracking = MerchantIds::whereIn('tracking_id',$failed_tracking_Ids)->pluck('tracking_id')->toArray();
                $ctc = DB::table('ctc_failed_orders')->whereIn('tracking_num',$merchnatTracking)->get(['tracking_num','customer_name','address','merchant_order_number']);
            }
            if ($type == 'failed_not_create') {
                $merchnatTracking = MerchantIds::whereIn('tracking_id',$failed_tracking_Ids)->pluck('tracking_id')->toArray();
                $not_create_tracking  = array_diff($failed_tracking_Ids, $merchnatTracking);
                $ctc = DB::table('ctc_failed_orders')->whereIn('tracking_num',$not_create_tracking)->get(['tracking_num','customer_name','address','merchant_order_number']);
            }

        }

        $result = array_merge($montreal,$ottawa,$ctc);
        return backend_view('statistics.failed_detail', compact('result','title'));
    }
    // end view file function

    public function getFailedCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
        $failed_order = 0;
        $system_failed_order = 0;
        $not_in_system_failed_order = 0;

        if (in_array('477661', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $failed_tracking_Ids = DB::table('xml_failed_orders')->join('mainfest_fields', 'mainfest_fields.trackingID', '=', 'xml_failed_orders.tracking_id')
                    ->where('xml_failed_orders.created_at','>',$start)->where('xml_failed_orders.created_at','<',$end)->whereNotNull('mainfest_fields.trackingID')
                    ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477661])->pluck('tracking_id');

            $merchnatTracking = MerchantIds::whereIn('tracking_id', $failed_tracking_Ids)->pluck('tracking_id')->toArray();
            $failed_order = $failed_order + count($failed_tracking_Ids);
            $system_failed_order = $system_failed_order + count($merchnatTracking);
            $not_in_system_failed_order = $not_in_system_failed_order + count(array_diff($failed_tracking_Ids, $merchnatTracking));


        }

//        if (in_array('477282', $vendors)) {
//            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));
//
//            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//            $failed_tracking_Ids = DB::table('xml_failed_orders')->join('mainfest_fields', 'mainfest_fields.trackingID', '=', 'xml_failed_orders.tracking_id')
//                    ->where('xml_failed_orders.created_at','>',$start)->where('xml_failed_orders.created_at','<',$end)->whereNotNull('mainfest_fields.trackingID')
//                    ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477282])->pluck('tracking_id');
//            $merchnatTracking = MerchantIds::whereIn('tracking_id',$failed_tracking_Ids)->pluck('tracking_id')->toArray();
//            $failed_order = $failed_order + count($failed_tracking_Ids);
//            $system_failed_order = $system_failed_order + count($merchnatTracking);
//            $not_in_system_failed_order = $not_in_system_failed_order + count(array_diff($failed_tracking_Ids, $merchnatTracking));
//        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $failed_tracking_Ids = DB::table('ctc_failed_orders')->whereIn('vendor_id' ,$ctc_ids)
                    ->where('ctc_failed_orders.created_at','>',$start)->where('ctc_failed_orders.created_at','<',$end)->pluck('tracking_num');
            $merchnatTracking = MerchantIds::whereIn('tracking_id',$failed_tracking_Ids)->pluck('tracking_id')->toArray();
            $failed_order = $failed_order + count($failed_tracking_Ids);
            $system_failed_order = $system_failed_order + count($merchnatTracking);
            $not_in_system_failed_order = $not_in_system_failed_order + count(array_diff($failed_tracking_Ids, $merchnatTracking));

        }
        $counts['failed'] = $failed_order;
        $counts['system_failed_order'] = $system_failed_order;
        $counts['not_in_system_failed_order'] = $not_in_system_failed_order;

        return $counts;
    }

    public function getCustomCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();

        $custom_order = 0;

        if (in_array('477661', $vendors)) {

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $custom_order = $custom_order + DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477661])->where('is_custom_route', 1)->count();
        }
//        if (in_array('477282', $vendors)) {
//
//            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//
//            $custom_order = $custom_order + DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
//                    ->where(['creator_id' => 477282])->where('is_custom_route', 1)->count();
//        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $custom_order = $custom_order + DB::table('ctc_entries')->whereIn('creator_id',$ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->where('is_custom_route', 1)->count();
        }
        $counts['custom_order'] = $custom_order;
        return $counts;
    }

    public function getManualCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
        $manual = 0;

        if (in_array('477661', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $trackingIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477661])->pluck('tracking_id');


            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
        }
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
//
//            $trackingIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
//                ->where(['creator_id' => 477282])->pluck('tracking_id');
//
//            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
//            $start_dt->setTimeZone(new DateTimezone('UTC'));
//            $start = $start_dt->format('Y-m-d H:i:s');
//
//            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
//            $end_dt->setTimeZone(new DateTimezone('UTC'));
//            $end = $end_dt->format('Y-m-d H:i:s');
//
//            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
//                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
//        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $trackingIds = DB::table('ctc_entries')->whereIn('creator_id',$ctc_ids)
                ->where('created_at','>',$start)->where('created_at','<',$end)->pluck('tracking_id');
            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
        }
        $counts['manual'] = $manual;

        return $counts;
    }

    // review
    public function getRouteDataCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();

        $routeIds = [];
        $ottawa_routeIds = [];
        $montreal_routeIds = [];
        $ctc_routeIds = [];
        $total_route = 0;
        $normal_route = 0;
        $custom_route = 0;
        $big_box_route = 0;

 
        if (in_array('477661', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
            //$montreal_routeIds = AmazonEnteries::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")->where(['creator_id' => 477260])->pluck('route_id')->toArray();
             $montreal_routeIds = JoeyRoutes::where('hub',16)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }
//        if (in_array('477282', $vendors)) {
//            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
//            //$ottawa_routeIds = AmazonEnteries::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")->where(['creator_id' => 477282])->pluck('route_id')->toArray();
//             $ottawa_routeIds = JoeyRoutes::where('hub',19)->where('date', 'like', $date . "%")->pluck('id')->toArray();
//        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
            //$ctc_routeIds = CTCEntry::whereIn('creator_id',$ctc_ids)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")->pluck('route_id')->toArray();
              $ctc_routeIds = JoeyRoutes::where('hub',17)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }
        $routeIds = array_merge($montreal_routeIds,$ottawa_routeIds,$ctc_routeIds);

        $route_data = JoeyRoutes::whereIn('id',$routeIds)->where('date', 'like', $date . "%")->whereNull('deleted_at')->get();
        foreach ($route_data as $route){
            $route_location_check  = DB::table('joey_route_locations')->where('route_id', $route->id)->whereNull('deleted_at')->first();
            if ($route_location_check) {
                if ($route->zone != null) {
                    $is_custom_check = \DB::table("zones_routing")->where('id', $route->zone)->whereNull('is_custom_routing')->first();
                    if ($is_custom_check) {
                        $normal_route++;
                    } else {
                        $route_location = DB::table('joey_route_locations')->where('route_id', $route->id)->first();
                        if ($route_location) {
                            $tracking = DB::table('merchantids')->where('task_id', $route_location->task_id)->first();
                            if ($tracking) {
                                $custom_route_data = DB::table('custom_routing_tracking_id')->where('tracking_id', $tracking->tracking_id)->first();
                                if ($custom_route_data) {
                                    if ($custom_route_data->is_big_box == 1) {
                                        $big_box_route++;
                                    } else {
                                        $custom_route++;
                                    }
                                } else {
                                    $custom_route++;
                                }
                            } else {
                                $custom_route++;
                            }
                        } else {
                            $custom_route++;
                        }
                    }
                } else {
                    $route_location = DB::table('joey_route_locations')->where('route_id', $route->id)->first();
                    if ($route_location) {
                        $tracking = DB::table('merchantids')->where('task_id', $route_location->task_id)->first();
                        if ($tracking) {
                            $custom_route_data = DB::table('custom_routing_tracking_id')->where('tracking_id', $tracking->tracking_id)->first();
                            if ($custom_route_data) {
                                if ($custom_route_data->is_big_box == 1) {
                                    $big_box_route++;
                                } else {
                                    $custom_route++;
                                }
                            } else {
                                $custom_route++;
                            }
                        } else {
                            $custom_route++;
                        }
                    } else {
                        $custom_route++;
                    }
                }
                $total_route++;
            }
        }
        $counts['total_route']= $total_route;
        $counts['normal_route']= $normal_route;
        $counts['custom_route']= $custom_route;
        $counts['big_box_route']= $big_box_route;
        return $counts;
    }

    public function getRouteDataDetail(Request $request)
    {
        $input = $request->all();
        $type = base64_decode($input['type']);
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $routeIds = [];
        $ottawa_routeIds = [];
        $montreal_routeIds = [];
        $ctc_routeIds = [];

        $total_route = [];
        $normal_route = [];
        $custom_route = [];
        $big_box_route = [];



        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
            //$montreal_routeIds = AmazonEnteries::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")->where(['creator_id' => 477260])->pluck('route_id')->toArray();
         $montreal_routeIds = JoeyRoutes::where('hub',16)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }
        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
            //$ottawa_routeIds = AmazonEnteries::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")->where(['creator_id' => 477282])->pluck('route_id')->toArray();
             $ottawa_routeIds = JoeyRoutes::where('hub',19)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
            //$ctc_routeIds = CTCEntry::whereIn('creator_id',$ctc_ids)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")->pluck('route_id')->toArray();
            $ctc_routeIds = JoeyRoutes::where('hub',17)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }
        $routeIds = array_merge($montreal_routeIds,$ottawa_routeIds,$ctc_routeIds);

        $route_data = JoeyRoutes::whereIn('id',$routeIds)->where('date', 'like', $date . "%")->whereNull('deleted_at')->get();
        foreach ($route_data as $route){
            $route_location_check  = DB::table('joey_route_locations')->where('route_id', $route->id)->whereNull('deleted_at')->first();
            if ($route_location_check) {
                if ($route->zone != null) {
                    $is_custom_check = \DB::table("zones_routing")->where('id', $route->zone)->whereNull('is_custom_routing')->first();
                    if ($is_custom_check) {
                        $normal_route[] = $route->id;
                    } else {
                        $route_location = DB::table('joey_route_locations')->where('route_id', $route->id)->first();
                        if ($route_location) {
                            $tracking = DB::table('merchantids')->where('task_id', $route_location->task_id)->first();
                            if ($tracking) {
                                $custom_route_data = DB::table('custom_routing_tracking_id')->where('tracking_id', $tracking->tracking_id)->first();
                                if ($custom_route_data) {
                                    if ($custom_route_data->is_big_box == 1) {
                                        $big_box_route[] = $route->id;
                                    } else {
                                        $custom_route[] = $route->id;
                                    }
                                } else {
                                    $custom_route[] = $route->id;
                                }
                            } else {
                                $custom_route[] = $route->id;
                            }
                        } else {
                            $custom_route[] = $route->id;
                        }
                    }
                } else {
                    $route_location = DB::table('joey_route_locations')->where('route_id', $route->id)->first();
                    if ($route_location) {
                        $tracking = DB::table('merchantids')->where('task_id', $route_location->task_id)->first();
                        if ($tracking) {
                            $custom_route_data = DB::table('custom_routing_tracking_id')->where('tracking_id', $tracking->tracking_id)->first();
                            if ($custom_route_data) {
                                if ($custom_route_data->is_big_box == 1) {
                                    $big_box_route[] = $route->id;
                                } else {
                                    $custom_route[] = $route->id;
                                }
                            } else {
                                $custom_route[] = $route->id;
                            }
                        } else {
                            $custom_route[] = $route->id;
                        }
                    } else {
                        $custom_route[] = $route->id;
                    }
                }
                $total_route[] = $route->id;
            }
        }
        $route_details = [];
        if ($type == 'total_route') {
            $title = 'Total Route';
            $route_details = JoeyRoutes::whereIn('id',$routeIds)->whereIn('id',$total_route)->whereNull('deleted_at')->get();
        }
        elseif ($type == 'normal_route') {
            $title = 'Normal Route';
            $route_details = JoeyRoutes::whereIn('id',$routeIds)->whereIn('id',$normal_route)->whereNull('deleted_at')->get();
        }
        elseif($type == 'custom_route') {
            $title = 'Custom Route';
            $route_details = JoeyRoutes::whereIn('id',$routeIds)->whereIn('id',$custom_route)->whereNull('deleted_at')->get();
        }
        else {
            $title = 'Big Box Route';
            $route_details = JoeyRoutes::whereIn('id',$routeIds)->whereIn('id',$big_box_route)->whereNull('deleted_at')->get();
        }

        return backend_view('statistics.route_detail', compact('route_details','title'));
    }
	// end review

//    public function getOnTimeCounts(Request $request)
//    {
//        $input = $request->all();
//        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
//        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
//        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
//        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
//        $setting = AlertSystem::where('hub_id', $hub_id)->first();
//
//        $sorted_hour = 0;
//        $sorted_mint = 0;
//        $pickup_hour = 0;
//        $pickup_mint = 0;
//
//        $montreal_sort_TrackingIds = [];
//        $montreal_pick_TrackingIds = [];
//        $ottawa_sort_TrackingIds = [];
//        $ottawa_pick_TrackingIds = [];
//        $ctc_sort_TrackingIds = [];
//        $ctc_pick_TrackingIds = [];
//
//        if (in_array('477661', $vendors)) {
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
//            $montreal_tracking_ids = AmazonEnteries::where('creator_id', '477661')->where('created_at','>',$start)->where('created_at','<',$end)
//                ->pluck('tracking_id')->toArray();
//            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$montreal_tracking_ids)->pluck('tracking_id')->toArray();
//            $montreal_sort_TrackingIds = array_diff($montreal_tracking_ids,$Custom_tracking_ids);
//
//            /*$montreal_tracking_ids = AmazonEnteries::where('creator_id', '477260')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")
//                ->pluck('tracking_id')->toArray();
//            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$montreal_tracking_ids)->pluck('tracking_id')->toArray();
//            $montreal_pick_TrackingIds = array_diff($montreal_tracking_ids,$Custom_tracking_ids);*/
//        }
////        if (in_array('477282', $vendors)) {
////            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));
////
////            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
////            $start_dt->setTimeZone(new DateTimezone('UTC'));
////            $start = $start_dt->format('Y-m-d H:i:s');
////
////            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
////            $end_dt->setTimeZone(new DateTimezone('UTC'));
////            $end = $end_dt->format('Y-m-d H:i:s');
////
////
////            $ottawa_tracking_ids = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
////                ->pluck('tracking_id')->toArray();
////            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$ottawa_tracking_ids)->pluck('tracking_id')->toArray();
////            $ottawa_sort_TrackingIds = array_diff($ottawa_tracking_ids,$Custom_tracking_ids);
////
////            /*$ottawa_tracking_ids = AmazonEnteries::where('creator_id', '477282')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")
////                ->pluck('tracking_id')->toArray();
////            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$ottawa_tracking_ids)->pluck('tracking_id')->toArray();
////            $ottawa_pick_TrackingIds = array_diff($ottawa_tracking_ids,$Custom_tracking_ids);*/
////        }
//        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
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
//            $ctc_tracking_ids = CTCEntry::whereIn('creator_id',$ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
//                ->pluck('tracking_id')->toArray();
//            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$ctc_tracking_ids)->pluck('tracking_id')->toArray();
//            $ctc_sort_TrackingIds = array_diff($ctc_tracking_ids,$Custom_tracking_ids);
//
//           /* $ctc_tracking_ids = CTCEntry::whereIn('creator_id',$ctc_ids)->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
//                ->pluck('tracking_id')->toArray();
//            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$ctc_tracking_ids)->pluck('tracking_id')->toArray();
//            $ctc_pick_TrackingIds = array_diff($ctc_tracking_ids,$Custom_tracking_ids);*/
//        }
//
//        $sort = array_merge($montreal_sort_TrackingIds,$ottawa_sort_TrackingIds,$ctc_sort_TrackingIds);
//
//        //$pick = array_merge($montreal_pick_TrackingIds,$ottawa_pick_TrackingIds,$ctc_pick_TrackingIds);
//
//        $sort_task_ids = MerchantIds::whereIn('tracking_id',$sort)->pluck('task_id')->toArray();
//
//        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
//        $start_dt->setTimeZone(new DateTimezone('UTC'));
//        $start = $start_dt->format('Y-m-d H:i:s');
//
//        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
//        $end_dt->setTimeZone(new DateTimezone('UTC'));
//        $end = $end_dt->format('Y-m-d H:i:s');
//
//
//        $first_sort_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->where('status_id','133')->where('created_at','>',$start)->where('created_at','<',$end)
//            ->orderBy('created_at', 'ASC')->first();
//        $last_sort_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->where('status_id','133')->where('created_at','>',$start)->where('created_at','<',$end)
//            ->orderBy('created_at', 'DESC')->first();
//
//        if (!empty($first_sort_order)) {
//            if ($first_sort_order->created_at) {
//                $date1 = new \DateTime($first_sort_order->created_at);
//                $date2 = new \DateTime($last_sort_order->created_at);
//                $diff = $date2->diff($date1);
//                if ($diff->d > 0) {
//                    $sorted_hour = $sorted_hour + $diff->d * 24;
//                }
//                $sorted_hour = $sorted_hour + $diff->h;
//                $sorted_mint = $sorted_mint + $diff->i;
//            }
//        }
//
//        //$pick_task_ids = MerchantIds::whereIn('tracking_id',$pick)->pluck('task_id')->toArray();
//
//        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
//        $start_dt->setTimeZone(new DateTimezone('UTC'));
//        $start = $start_dt->format('Y-m-d H:i:s');
//
//        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
//        $end_dt->setTimeZone(new DateTimezone('UTC'));
//        $end = $end_dt->format('Y-m-d H:i:s');
//
//        $first_pick_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->where('status_id','121')->where('created_at','>',$start)->where('created_at','<',$end)
//            ->orderBy('created_at', 'ASC')->first();
//        $last_pick_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->where('status_id','121')->where('created_at','>',$start)->where('created_at','<',$end)
//            ->orderBy('created_at', 'DESC')->first();
//
//        if (!empty($first_pick_order)) {
//            if ($first_pick_order->created_at) {
//                $date1 = new \DateTime($first_pick_order->created_at);
//                $date2 = new \DateTime($last_pick_order->created_at);
//                $diff = $date2->diff($date1);
//                if ($diff->d > 0) {
//                    $pickup_hour = $pickup_hour + $diff->d * 24;
//                }
//                $pickup_hour = $pickup_hour + $diff->h;
//                $pickup_mint = $pickup_mint + $diff->i;
//            }
//        }
//        if ($setting) {
//            if ($setting->sorting_time <= $sorted_hour) {
//                if ($sorted_mint > 0) {
//                    $setting->sendEmail($subject = 'Sorting Time Alert', $email = 'ahmed@joeyco.com', $name = 'JoeyCo', $message = 'You are receiving this email because sorting time greater then system sorted time.');
//                }
//            }
//
//            if ($setting->pickup_time <= $pickup_hour) {
//                if ($pickup_mint > 0) {
//                    $setting->sendEmail($subject = 'PickUp Time Alert', $email = 'ahmed@joeyco.com', $name = 'JoeyCo', $message = 'You are receiving this email because pickup time greater then system pickup time.');
//                }
//            }
//        }
//        if($sorted_hour < 10){
//            $sorted_hour = '0'.$sorted_hour;
//        }
//        if($sorted_mint < 10){
//            $sorted_mint = '0'.$sorted_mint;
//        }
//        if($pickup_hour < 10){
//            $pickup_hour = '0'.$pickup_hour;
//        }
//        if($pickup_mint < 10){
//            $pickup_mint = '0'.$pickup_mint;
//        }
//
//        $counts['sorting'] = $sorted_hour.':'.$sorted_mint;
//        $counts['pickup'] = $pickup_hour.':'.$pickup_mint;
//        return  $counts;
//    }

    public function getTopTenJoeys(Request $request)
    {
        $input = $request->all();
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $joeyList =[];
        $joeyTrackingListList =[];

        if (in_array('477661', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $query = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477661])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at)) {
                            if ($record->joey_id) {
                                if ($record->delivered_at <= $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }

                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at)) {
                            if ($record->joey_id) {
                                if ($record->returned_at <= $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                }
            }
        }
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
//            $query = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
//                ->where(['creator_id' => 477282])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//            if (!empty($query)) {
//                foreach ($query as $record) {
//                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
//                        if (!is_null($record->delivered_at)) {
//                            if ($record->joey_id) {
//                                if ($record->delivered_at <= $date . " 21:00:00") {
//                                    if (!isset($joeyList[$record->joey_id])) {
//                                        $joeyList[$record->joey_id] = 1;
//                                    } else {
//                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
//                                    }
//                                }
//                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
//                            }
//                        }
//                    }
//                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
//                        if (!is_null($record->returned_at) ) {
//                            if ($record->joey_id) {
//                                if ($record->returned_at <= $date . " 21:00:00") {
//                                    if (!isset($joeyList[$record->joey_id])) {
//                                        $joeyList[$record->joey_id] = 1;
//                                    } else {
//                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
//                                    }
//                                }
//                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
//                            }
//                        }
//                    }
//                }
//            }
//        }
        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
            $ctc_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at)) {
                            if ($record->joey_id) {
                                if ($record->delivered_at <= $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at)) {
                            if ($record->joey_id) {
                                if ($record->returned_at <= $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                }
            }
        }
        arsort($joeyList);

        $joeyList = array_slice($joeyList, 0, 10, true);

        $deliverytime=[];
        $ststausList = array_merge($sprint->getStatusCodes('competed'),$sprint->getStatusCodes('return'));
        foreach ($joeyList as $key=>$value)
        {
            $sort_task_ids = MerchantIds::whereIn('tracking_id',$joeyTrackingListList[$key])->pluck('task_id')->toArray();

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $first_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where('created_at','>',$start)->where('created_at','<',$end)
                ->orderBy('created_at', 'ASC')->first();
            $last_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where('created_at','>',$start)->where('created_at','<',$end)
                ->orderBy('created_at', 'DESC')->first();
            $drop_hour= 0;
            $drop_mint = 0;
            if (!empty($first_drop_order)) {
                if ($first_drop_order->created_at) {
                    $date1 = new \DateTime($first_drop_order->created_at);
                    $date2 = new \DateTime($last_drop_order->created_at);
                    $diff = $date2->diff($date1);
                    if ($diff->d > 0) {
                        $drop_hour = $drop_hour + $diff->d * 24;
                    }
                    $drop_hour = $drop_hour + $diff->h;
                    $drop_mint = $drop_mint + $diff->i;
                }
            }
            $total_mint = ($drop_hour * 60) + $drop_mint;
            if ($total_mint) {
                $deliverytime[$key] = ($value / $total_mint) * 100;
            }
            else{
                $deliverytime[$key] = 0;
            }
        }
        arsort($deliverytime);


        $finalList = [];
        $i = 0;
        foreach ($deliverytime as $key=>$value)
        {
            $joey =Joey::where('id',$key)->first();
            $finalList[$i]['name'] = $joey->first_name." ".$joey->last_name;
            $finalList[$i]['image'] = $joey->image != null ? $joey->image : url('/').'/images/profile_images/default.png';
            $finalList[$i]['count'] = $joeyList[$key];
            $finalList[$i]['joey_id'] = $joey->id;
            $finalList[$i]['encode_joey_id'] = base64_encode($joey->id);
            $i++;
        }
        return $finalList;
    }

    public function getLeastTenJoeys(Request $request)
    {
        $input = $request->all();
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $joeyList =[];
        $joeyTrackingListList =[];

        if (in_array('477661', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477661])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at)) {
                            if ($record->joey_id) {
                                if ($record->delivered_at > $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at)) {
                            if ($record->joey_id) {
                                if ($record->returned_at > $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                }
            }
        }

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
//
//            $ottawaSprintIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
//                ->where(['creator_id' => 477282])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//            if (!empty($query)) {
//                foreach ($query as $record) {
//                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
//                        if (!is_null($record->delivered_at) ) {
//                            if ($record->joey_id) {
//                                if ($record->delivered_at > $date . " 21:00:00") {
//                                    if (!isset($joeyList[$record->joey_id])) {
//                                        $joeyList[$record->joey_id] = 1;
//                                    } else {
//                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
//                                    }
//                                }
//                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
//                            }
//                        }
//                    }
//                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
//                        if (!is_null($record->returned_at)) {
//                            if ($record->joey_id) {
//                                if ($record->returned_at > $date . " 21:00:00") {
//                                    if (!isset($joeyList[$record->joey_id])) {
//                                        $joeyList[$record->joey_id] = 1;
//                                    } else {
//                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
//                                    }
//                                }
//                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
//                            }
//                        }
//                    }
//                }
//            }
//        }
        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
            $ctc_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at)) {
                            if ($record->joey_id) {
                                if ($record->delivered_at > $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at)) {
                            if ($record->joey_id) {
                                if ($record->returned_at > $date . " 21:00:00") {
                                    if (!isset($joeyList[$record->joey_id])) {
                                        $joeyList[$record->joey_id] = 1;
                                    } else {
                                        $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                    }
                                }
                                $joeyTrackingListList[$record->joey_id][]= $record->tracking_id;
                            }
                        }
                    }
                }
            }

        }
        arsort($joeyList);
        $joeyList = array_slice($joeyList, 0, 10, true);

        $deliverytime=[];
        $ststausList = array_merge($sprint->getStatusCodes('competed'),$sprint->getStatusCodes('return'));
        foreach ($joeyList as $key=>$value)
        {
            $sort_task_ids = MerchantIds::whereIn('tracking_id',$joeyTrackingListList[$key])->pluck('task_id')->toArray();

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $first_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where('created_at','>',$start)->where('created_at','<',$end)
                ->orderBy('created_at', 'ASC')->first();
            $last_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where('created_at','>',$start)->where('created_at','<',$end)
                ->orderBy('created_at', 'DESC')->first();
            $drop_hour= 0;
            $drop_mint = 0;
            if (!empty($first_drop_order)) {
                if ($first_drop_order->created_at) {
                    $date1 = new \DateTime($first_drop_order->created_at);
                    $date2 = new \DateTime($last_drop_order->created_at);
                    $diff = $date2->diff($date1);
                    if ($diff->d > 0) {
                        $drop_hour = $drop_hour + $diff->d * 24;
                    }
                    $drop_hour = $drop_hour + $diff->h;
                    $drop_mint = $drop_mint + $diff->i;
                }
            }
            $total_mint = ($drop_hour * 60) + $drop_mint;
            if ($total_mint) {
                $deliverytime[$key] = ($value / $total_mint) * 100;
            }
            else{
                $deliverytime[$key] = 0;
            }
        }
        arsort($deliverytime);
        $finalList = [];
        $i = 0;
        foreach ($deliverytime as $key=>$value)
        {
            $joey =Joey::where('id',$key)->first();
            $finalList[$i]['name'] = $joey->first_name." ".$joey->last_name;
            $finalList[$i]['image'] = $joey->image != null ? $joey->image : url('/').'/images/profile_images/default.png';
            $finalList[$i]['count'] = $joeyList[$key];
            $finalList[$i]['joey_id'] = $joey->id;
            $finalList[$i]['encode_joey_id'] = base64_encode($joey->id);
            $i++;
        }

        return $finalList;
    }

    public function getGraph(Request $request)
    {
        $input = $request->all();
        $type = $input['type'];
        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
//        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $all_dates = [];
        if ($type == 'week') {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 day', strtotime($date))));
        }
        else
        {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-1 month', strtotime($date))));
        }
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        $odt_data_1=[];

        foreach ($all_dates as $range_date) {
            $totalcount = 0;
            $totallates = 0;
//            if (in_array('477661', $vendors)) {
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($range_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($range_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = BoradlessDashboard::where('creator_id', '477661')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                $totallates++;
                            }
                        }
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
                                $totallates++;
                            }
                        }
                        $totalcount++;
                    }
                }
//            }

//            if (in_array('477282', $vendors)) {
//                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));
//
//                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }

//            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
//                $ctc_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));
//                $ctc_ids = array_intersect($ctcVendorIds, $vendors);
//
//                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
//                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
//                    ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
//                                $totallates++;
//                            }
//                        }
//                        $totalcount++;
//                    }
//                }
//            }
            if ($totalcount == 0)
            {
                $totalcount = 1;
            }
            $odt_data_1[$range_date] = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2),'ontime'=>  $totalcount - $totallates  , 'offtime'=> $totallates];
        }

        return $odt_data_1;
    }

    public function getBroker(Request $request)
    {
        $input = $request->all();

        $date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
        $vendor_id = isset($input['vendor_id']) ?  $input['vendor_id'] : 477661;
        $vendors = Vendor::where('id', $vendor_id)->pluck('id')->toArray();
        $ctcVendorIds = CtcVendor::where('vendor_id', $vendor_id)->pluck('vendor_id')->toArray();
        $brokers = BrookerUser::whereNull('deleted_at')->get();
        $sprint = new Sprint();
        $brookerList = [];
        $joeyTrackingListList=[];

        foreach ($brokers as $broker) {
            $totalcount = 0;
            $totalonTime = 0;
            $brooker_joeys = BrookerJoey::where('brooker_id',$broker->id)->pluck('joey_id')->toArray();

            if (in_array('477661', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = AmazonEnteries::where('creator_id', '477661')->whereIn('joey_id',$brooker_joeys)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->get(['tracking_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);

                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at) && $record->delivered_at <= $date . " 21:00:00") {
                                $totalonTime++;
                            }
                          $joeyTrackingListList[$broker->id][]= $record->tracking_id;
                        }
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at) && $record->returned_at <= $date . " 21:00:00") {
                                $totalonTime++;
                            }
                            $joeyTrackingListList[$broker->id][]= $record->tracking_id;
                        }
                        $totalcount++;
                    }
                }
            }

//            if (in_array('477282', $vendors)) {
//                $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
//
//                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
//                $start_dt->setTimeZone(new DateTimezone('UTC'));
//                $start = $start_dt->format('Y-m-d H:i:s');
//
//                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
//                $end_dt->setTimeZone(new DateTimezone('UTC'));
//                $end = $end_dt->format('Y-m-d H:i:s');
//
//
//                $query = AmazonEnteries::where('creator_id', '477282')->whereIn('joey_id',$brooker_joeys)
//                    ->where('created_at','>',$start)->where('created_at','<',$end)->get(['tracking_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
//                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
//                if (!empty($query)) {
//                    foreach ($query as $record) {
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
//                            if (!!is_null($record->delivered_at) && $record->delivered_at <= $date . " 21:00:00") {
//                                $totalonTime++;
//                            }
//                            $joeyTrackingListList[$broker->id][]= $record->tracking_id;
//                        }
//                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
//                            if (!is_null($record->returned_at) && $record->returned_at <= $date . " 21:00:00") {
//                                $totalonTime++;
//                            }
//                            $joeyTrackingListList[$broker->id][]= $record->tracking_id;
//                        }
//                        $totalcount++;
//                    }
//                }
//            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);
                $ctc_date = date('Y-m-d', strtotime($date . ' -1 days'));

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)
                    ->whereIn('joey_id',$brooker_joeys)->whereIn('sprint_id', $sprint_id)->get(['tracking_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at) && $record->delivered_at <= $date . " 21:00:00") {
                                $totalonTime++;
                            }
                            $joeyTrackingListList[$broker->id][]= $record->tracking_id;
                        }
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at) && $record->returned_at <= $date . " 21:00:00") {
                                $totalonTime++;
                            }
                            $joeyTrackingListList[$broker->id][]= $record->tracking_id;
                        }
                        $totalcount++;
                    }
                }
            }
            if($totalcount > 0) {
                $brookerList[$broker->id] = $totalonTime;
            }

        }
        arsort($brookerList);
        //dd($brookerList);
        $brookerList = array_slice($brookerList, 0, 10, true);

        $deliverytime=[];
        $ststausList = array_merge($sprint->getStatusCodes('competed'),$sprint->getStatusCodes('return'));
        foreach ($brookerList as $key=>$value)
        {
            $sort_task_ids = MerchantIds::whereIn('tracking_id',$joeyTrackingListList[$key])->pluck('task_id')->toArray();

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $first_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where('created_at','>',$start)->where('created_at','<',$end)
                ->orderBy('created_at', 'ASC')->first();
            $last_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where('created_at','>',$start)->where('created_at','<',$end)
                ->orderBy('created_at', 'DESC')->first();
            $drop_hour= 0;
            $drop_mint = 0;
            if (!empty($first_drop_order)) {
                if ($first_drop_order->created_at) {
                    $date1 = new \DateTime($first_drop_order->created_at);
                    $date2 = new \DateTime($last_drop_order->created_at);
                    $diff = $date2->diff($date1);
                    if ($diff->d > 0) {
                        $drop_hour = $drop_hour + $diff->d * 24;
                    }
                    $drop_hour = $drop_hour + $diff->h;
                    $drop_mint = $drop_mint + $diff->i;
                }
            }
            $total_mint = ($drop_hour * 60) + $drop_mint;
            if ($total_mint) {
                $deliverytime[$key] = ($value / $total_mint) * 100;
            }
            else{
                $deliverytime[$key] = 0;
            }
        }
        arsort($deliverytime);

        $finalList = [];
        $i = 0;
        foreach ($deliverytime as $key=>$value)
        {
            $brooker =BrookerUser::where('id',$key)->first();
            $finalList[$i]['name'] = $brooker->name;
            $finalList[$i]['count'] = $brookerList[$key];
            $finalList[$i]['brooker_id'] = base64_encode($brooker->id);
            $i++;
        }

        return $finalList;
    }

    public function receivedAtHubQuery($query, $createdAt)
    {
        $totalcount = 0;
        $totalearly = 0;
        $totallates = 0;
        if (!empty($query)) {
            foreach ($query as $key => $record) {
//                if(isset($createdAt[$key])){
                    $startDatePlusOneDay = date('Y-m-d', strtotime($createdAt[$key] . ' +1 day'));
                    if (!is_null($record['delivered_at']) && $record['delivered_at'] > $startDatePlusOneDay . " 21:00:00") {
                        $totallates++;
                    }
                    if (!is_null($record['delivered_at']) && $record['delivered_at'] < $startDatePlusOneDay . " 21:00:00") {
                        $totalearly++;
                    }
                    if (!is_null($record['returned_at']) && is_null($record['delivered_at']) && $record['returned_at'] > $startDatePlusOneDay . " 21:00:00") {
                        $totallates++;
                    }
                    if (!is_null($record['returned_at']) && is_null($record['delivered_at']) && $record['returned_at'] < $startDatePlusOneDay . " 21:00:00") {
                        $totalearly++;
                    }
                    $totalcount++;
//                }
            }
        }

        return [
            'totalCount' => $totalcount,
            'totalEarly' => $totalearly,
            'totalLates' => $totallates
        ];


    }
}
