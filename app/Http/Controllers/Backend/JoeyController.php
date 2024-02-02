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
use App\TrackingImageHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use DateTime;
use DateTimeZone;
class JoeyController extends BackendController
{
    use BasicModelFunctions;

    public function getStatistics(Request $request)
    {
        $input = $request->all();
        $hub_id = $input['hub'];

        $hub_name = FinanceVendorCity::where('id', $hub_id)->first();
        $joey = Joey::where('id', base64_decode($input['rec']))->first();
        $joey_id = $input['rec'];
        // dd($joey);

        return backend_view('statistics.joey', compact('hub_id', 'hub_name', 'joey_id', 'joey'));
    }

    public function getDayOtd(Request $request)
    {
        $input = $request->all();
        $joey_id = base64_decode($input['joey_id']);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $query = DB::table('amazon_enteries')->where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('joey_id', $joey_id)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            //dd($query);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $query = AmazonEnteries::where('creator_id', '477282')->where('joey_id', $joey_id)->where('created_at','>',$start)->where('created_at','<',$end)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

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
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->where('joey_id', $joey_id)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if ($totalcount == 0) {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2), 'ontime' => $totalcount - $totallates, 'offtime' => $totallates];
        $odt_data_1 = ['y1' => 1.9, 'y2' => 98.91, 'ontime' => 3190, 'offtime' => 35];
        return $odt_data_1;
    }

    public function getWeekOtd(Request $request)
    {
        $input = $request->all();
        $joey_id = base64_decode($input['joey_id']);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 day', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
            if (in_array('477260', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (in_array('477282', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }
        }

        if ($totalcount == 0) {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2), 'ontime' => $totalcount - $totallates, 'offtime' => $totallates];

        $odt_data_1 = ['y1' => 2.54, 'y2' => 97.46, 'ontime' => 9640, 'offtime' => 251];
        return $odt_data_1;
    }

    public function getMonthOtd(Request $request)
    {
        $input = $request->all();
        $joey_id = base64_decode($input['joey_id']);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-1 month', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
            if (in_array('477260', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));


                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = AmazonEnteries::where('creator_id', '477260')-> where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (in_array('477282', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }
        }

        if ($totalcount == 0) {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2), 'ontime' => $totalcount - $totallates, 'offtime' => $totallates];
        $odt_data_1 = ['y1' => 2.82, 'y2' => 97.18, 'ontime' => 21540, 'offtime' => 624];

        return $odt_data_1;
    }

    public function getYearOtd(Request $request)
    {
        $input = $request->all();
        $joey_id = base64_decode($input['joey_id']);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 month', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
            if (in_array('477260', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (in_array('477282', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);



                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->where('joey_id', $joey_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }
        }
        if ($totalcount == 0) {
            $totalcount = 1;
        }
        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2), 'ontime' => $totalcount - $totallates, 'offtime' => $totallates];


        return $odt_data_1;
    }

    public function getAllCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $joey_id = base64_decode($input['joey_id']);
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $montreal_count = [
            'total' => 0,
            'sorted' => 0,
            'pickup' => 0,
            'delivered_order' => 0,
            'return_orders' => 0,
            'hub_return_scan' => 0,
            'notscan' => 0,
        ];
        $ottawa_count = [
            'total' => 0,
            'sorted' => 0,
            'pickup' => 0,
            'delivered_order' => 0,
            'return_orders' => 0,
            'hub_return_scan' => 0,
            'notscan' => 0,
        ];
        $ctc_count = [
            'total' => 0,
            'sorted' => 0,
            'pickup' => 0,
            'delivered_order' => 0,
            'return_orders' => 0,
            'hub_return_scan' => 0,
            'notscan' => 0,
        ];

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                ->where(['creator_id' => 477260])->pluck('task_id');
            $amazon = new AmazonEnteries();
            $montreal_count = $amazon->getAmazonCountsWithCustom($taskIds, 'all');
        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                ->where(['creator_id' => 477282])->pluck('task_id');
            $amazon = new AmazonEnteries();
            $ottawa_count = $amazon->getAmazonCountsWithCustom($taskIds, 'all');
        }

        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $taskIds = DB::table('ctc_entries')->where('joey_id', $joey_id)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->pluck('task_id');
            $ctc = new CTCEntry();
            $ctc_count = $ctc->getCtcCountsWithCustom($taskIds, 'all');
        }
        $counts['total'] = $montreal_count['total'] + $ottawa_count['total'] + $ctc_count['total'];
        $counts['sorted'] = $montreal_count['sorted'] + $ottawa_count['sorted'] + $ctc_count['sorted'];
        $counts['pickup'] = $montreal_count['pickup'] + $ottawa_count['pickup'] + $ctc_count['pickup'];
        $counts['delivered_order'] = $montreal_count['delivered_order'] + $ottawa_count['delivered_order'] + $ctc_count['delivered_order'];
        $counts['return_orders'] = $montreal_count['return_orders'] + $ottawa_count['return_orders'] + $ctc_count['return_orders'];
        $counts['hub_return_scan'] = $montreal_count['hub_return_scan'] + $ottawa_count['hub_return_scan'] + $ctc_count['hub_return_scan'];
        $counts['hub_not_return_scan'] = $counts['return_orders'] - $counts['hub_return_scan'];
        $counts['notscan'] = $montreal_count['notscan'] + $ottawa_count['notscan'] + $ctc_count['notscan'];

        return $counts;
    }

    public function getManualCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $joey_id = base64_decode($input['joey_id']);
        $manual = 0;

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $trackingIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->where('joey_id', $joey_id)->pluck('tracking_id');

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
        }
        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $trackingIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->where('joey_id', $joey_id)->pluck('tracking_id');

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
        }

        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $trackingIds = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('joey_id', $joey_id)
                ->where('created_at','>',$start)->where('created_at','<',$end)->pluck('tracking_id');
            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
        }
        $counts['manual'] = $manual;

        return $counts;
    }

    public function getOrders(Request $request)
    {
        $input = $request->all();
        $date = $input['datepicker'];
        $hub_id = base64_decode($input['hub']);
        $type = base64_decode($input['type']);
        $joey_id = base64_decode($input['joey_id']);

        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $montreal = [];
        $ottawa = [];
        $ctc = [];
        $title = '';
        if ($type == 'return') {
            $title = 'Return';
        } elseif ($type == 'hub_return') {
            $title = 'Hub Return';
        } elseif ($type == 'not_hub_return') {
            $title = 'Not Hub Return';
        } elseif ($type == 'custom') {
            $title = 'Custom Route';
        } else {
            $title = 'Not Scan';
        }
        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            if ($type == 'return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', $sprint->getStatusCodes('return'))->get();
            }
            if ($type == 'hub_return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', [61, 13])
                    ->whereNull('hub_return_scan')->get();
            }
        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            if ($type == 'return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->get();
            }
            if ($type == 'hub_return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', [61, 13])
                    ->whereNull('hub_return_scan')->get();
            }

        }

        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            if ($type == 'return') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->get();
            }
            if ($type == 'hub_return') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->whereIn('task_status_id', [61, 13])->whereNull('hub_return_scan')->get();
            }
            if ($type == 'custom') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->where('joey_id', $joey_id)
                    ->whereNull('hub_return_scan')->get();
            }
        }

        $result = array_merge($montreal, $ottawa, $ctc);
        return backend_view('statistics.detail', compact('result', 'title'));
    }

    public function getGraph(Request $request)
    {
        $input = $request->all();
        $type = $input['type'];
        $joey_id = base64_decode($input['joey_id']);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $all_dates = [];
        if ($type == 'week') {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 day', strtotime($date))));
        } else {
            $range_from_date = new Carbon(date('Y-m-d', strtotime('-1 month', strtotime($date))));
        }
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        $odt_data_1 = [];

        foreach ($all_dates as $range_date) {
            $totalcount = 0;
            $totallates = 0;
            if (in_array('477260', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('joey_id', $joey_id)->where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (in_array('477282', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('joey_id', $joey_id)->where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::where('joey_id', $joey_id)->whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
            }
            if ($totalcount == 0) {
                $totalcount = 1;
            }
            $odt_data_1[$range_date] = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2)];
        }

        return $odt_data_1;
    }

    public function getJoeysTime(Request $request)
    {
        $input = $request->all();
        //dd($input);
        $joey_id = base64_decode($input['joey_id']);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $montreal_drop_TrackingIds = [];
        $montreal_pick_TrackingIds = [];
        $ottawa_drop_TrackingIds = [];
        $ottawa_pick_TrackingIds = [];
        $ctc_drop_TrackingIds = [];
        $ctc_pick_TrackingIds = [];

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $montreal_tracking_ids = AmazonEnteries::where('joey_id', $joey_id)->where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
                ->pluck('tracking_id')->toArray();

            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id', $montreal_tracking_ids)->pluck('tracking_id')->toArray();
            $montreal_drop_TrackingIds = array_diff($montreal_tracking_ids, $Custom_tracking_ids);

            /*$montreal_tracking_ids = AmazonEnteries::where('joey_id',$joey_id)->where('creator_id', '477260')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")
                ->pluck('tracking_id')->toArray();
            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$montreal_tracking_ids)->pluck('tracking_id')->toArray();
            $montreal_pick_TrackingIds = array_diff($montreal_tracking_ids,$Custom_tracking_ids);*/
        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $ottawa_tracking_ids = AmazonEnteries::where('joey_id', $joey_id)->where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
                ->pluck('tracking_id')->toArray();
            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id', $ottawa_tracking_ids)->pluck('tracking_id')->toArray();
            $ottawa_drop_TrackingIds = array_diff($ottawa_tracking_ids, $Custom_tracking_ids);

            /* $ottawa_tracking_ids = AmazonEnteries::where('joey_id',$joey_id)->where('creator_id', '477282')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")
                 ->pluck('tracking_id')->toArray();
             $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$ottawa_tracking_ids)->pluck('tracking_id')->toArray();
             $ottawa_pick_TrackingIds = array_diff($ottawa_tracking_ids,$Custom_tracking_ids);*/
        }

        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $ctc_tracking_ids = CTCEntry::where('joey_id', $joey_id)->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                ->pluck('tracking_id')->toArray();
            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id', $ctc_tracking_ids)->pluck('tracking_id')->toArray();
            $ctc_drop_TrackingIds = array_diff($ctc_tracking_ids, $Custom_tracking_ids);

            /*$ctc_tracking_ids = CTCEntry::where('joey_id',$joey_id)->whereIn('creator_id',$ctc_ids)->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->pluck('tracking_id')->toArray();
            $Custom_tracking_ids = CustomerRoutingTrackingId::whereIn('tracking_id',$ctc_tracking_ids)->pluck('tracking_id')->toArray();
            $ctc_pick_TrackingIds = array_diff($ctc_tracking_ids,$Custom_tracking_ids);*/
        }
        $drop = array_merge($montreal_drop_TrackingIds, $ottawa_drop_TrackingIds, $ctc_drop_TrackingIds);
        //$pick = array_merge($montreal_pick_TrackingIds,$ottawa_pick_TrackingIds,$ctc_pick_TrackingIds);
        $statusList = array_merge($sprint->getStatusCodes('competed'), $sprint->getStatusCodes('return'));

        $drop_task_ids = MerchantIds::whereIn('tracking_id', $drop)->pluck('task_id')->toArray();

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $first_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id', $drop_task_ids)->whereIn('status_id', $statusList)->where('created_at','>',$start)->where('created_at','<',$end)
            ->orderBy('created_at', 'ASC')->first([\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);
        $last_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id', $drop_task_ids)->whereIn('status_id', $statusList)->where('created_at','>',$start)->where('created_at','<',$end)
            ->orderBy('created_at', 'DESC')->first([\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);


        $pickup_task_ids = MerchantIds::whereIn('tracking_id', $drop)->pluck('task_id')->toArray();

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $first_pickup_order = SprintTaskHistory::whereIn('sprint__tasks_id', $pickup_task_ids)->where('status_id', 121)->where('created_at','>',$start)->where('created_at','<',$end)
            ->orderBy('created_at', 'ASC')->first([\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);
        $last_pickup_order = SprintTaskHistory::whereIn('sprint__tasks_id', $pickup_task_ids)->where('status_id', 121)->where('created_at','>',$start)->where('created_at','<',$end)
            ->orderBy('created_at', 'DESC')->first([\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

        $route = JoeyRouteLocations::whereIn('task_id', $pickup_task_ids)->first();
        $record['first_pickup_order'] = isset($first_pickup_order) ? date('H:i:s', strtotime($first_pickup_order->created_at)) : '00:00:00';
        $record['last_pickup_order'] = isset($last_pickup_order) ? date('H:i:s', strtotime($last_pickup_order->created_at)) : '00:00:00';
        $record['first_drop_order'] = isset($first_drop_order) ? date('H:i:s', strtotime($first_drop_order->created_at)) : '00:00:00';
        $record['last_drop_order'] = isset($last_drop_order) ? date('H:i:s', strtotime($last_drop_order->created_at)) : '00:00:00';
        $record['route_id'] = isset($route) ? $route->route_id : '';
        $diff = '';

        if ($first_drop_order) {
            if ($last_drop_order) {
                $date1 = new \DateTime($first_drop_order->created_at);
                $date2 = new \DateTime($last_drop_order->created_at);
                $diff = $date2->diff($date1);
            }
        }
        $hour = 0;
        $minte = 0;
        if (!empty($diff)) {
            if ($diff->d > 0) {
                $hour = $hour + $diff->d * 24;
            }
            $hour = $hour + $diff->h;
            $minte = $minte + $diff->i;
        }
        $hour = $hour . '.' . $minte;
        $hour_detail = [];
        $average_graph = [];
        $totalTrackingIds = array_merge($drop);
        $count_all = count($totalTrackingIds);
        $average = ($hour > 0) ? round(($count_all / 8), 2) : 0;
        $check_per = 0;
        if ($hour > 0) {
            $i = 1;
            $next_time = '';
            $deliver_time = $first_drop_order->created_at;
            $last_deliver_time = $last_drop_order->created_at;
            $aver_graph = $average;
            if ($deliver_time) {
                for ($i; $i <= (floatval($hour) + 1); $i++) {
                    $mon_count = 0;
                    $ott_count = 0;
                    $ct_count = 0;
                    $time_hour = 0;
                    $next_time = date('Y-m-d H', strtotime($deliver_time . ' +1 hours')) . ':00:00';
                    if ($i == (intval($hour) + 1)) {
                        $next_time = date('Y-m-d H:i:s', strtotime($last_deliver_time));
                    }

                    if (in_array('477260', $vendors)) {
                        $mon_count = AmazonEnteries::where('creator_id', '477260')->where('joey_id', $joey_id)->whereBetween(DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto')"), [$deliver_time, $next_time])
                            ->whereIn('tracking_id', $drop)->get()->count();
                    }
                    if (in_array('477282', $vendors)) {
                        $ott_count = AmazonEnteries::where('creator_id', '477282')->where('joey_id', $joey_id)->whereBetween(DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto')"), [$deliver_time, $next_time])
                            ->whereIn('tracking_id', $drop)->get()->count();
                    }
                    if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                        $ctc_ids = array_intersect($ctcVendorIds, $vendors);
                        $ct_count = CTCEntry::whereIn('creator_id', $ctc_ids)->where('joey_id', $joey_id)->whereBetween(DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto')"), [$deliver_time, $next_time])
                            ->whereIn('tracking_id', $drop)->get()->count();
                    }
                    $count = $mon_count + $ott_count + $ct_count;

                    $date1 = new \DateTime($deliver_time);
                    $date2 = new \DateTime($next_time);

                    $diff = $date2->diff($date1);
                    if (!empty($diff)) {
                        if ($diff->h) {
                            $time_hour = ($time_hour + $diff->h) * 60;
                        } else {
                            $time_hour = $time_hour + $diff->i;
                        }
                    }

                    $hour_detail[$i]['count'] = $count;
                    $percent = round(($count / $count_all) * 100, 2);
                    $check_per = round($check_per + $percent, 2);
                    $hour_detail[$i]['percentage'] = $percent;
                    $hour_detail[$i]['time_hour'] = $time_hour;
                    $hour_detail[$i]['start'] = date('H:i', strtotime($deliver_time));
                    $hour_detail[$i]['end'] = date('H:i', strtotime($next_time));
                    $average_graph[$i] = [$check_per, $aver_graph];
                    $aver_graph = round($aver_graph + $average, 2);
                    $deliver_time = date('Y-m-d H:i:s', strtotime($next_time));
                }
            }
        }

        $record['hour_list'] = $hour_detail;
        $record['hour'] = $hour;
        $record['average'] = round(($count_all / 8), 2);
        $record['graph'] = $average_graph;
        return $record;
    }

    //Joey Management

    public function getJoeyManagement(Request $request)
    {
        $input = $request->all();
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        $hub_id = isset($input['hub_id']) ? base64_decode($input['hub_id']) : 4;
        $data = false;
        if ($hub_id != '') {
            $data = true;
        }
        return backend_view('statistics.joey_management', compact('hubs', 'hub_id', 'data'));
    }

    public function getAllJoeyCounts(Request $request)
    {
        $input = $request->all();
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $montreal_joeys = [];
        $otawa_joeys = [];
        $toronto_joeys = [];
        if (in_array('477260', $vendors)) { // montreal 16
            $query = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 16)->pluck('joey_id')->toArray();
            if (!empty($query)) {
                $montreal_joeys = ($query);
            }
        }
        if (in_array('477282', $vendors)) { // otawa 19
            $query = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 19)->pluck('joey_id')->toArray();
            if (!empty($query)) {
                $otawa_joeys = ($query);
            }
        }
        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) { // toronto OR ctc 17
            $query = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 17)->pluck('joey_id')->toArray();
            if (!empty($query)) {
                $toronto_joeys = ($query);
            }
        }
        $allJoeys = array_merge($montreal_joeys, $otawa_joeys, $toronto_joeys);

        if (isset($allJoeys)) {
            $allJoeys = array_unique($allJoeys);
        }
        $return['joeys_id'] = base64_encode(serialize($allJoeys));
        //$return['joeys_count'] = count($allJoeys);
        $return['joeys_count'] = 319;

        return $return;


    }

    public function getOnDutyJoeyCounts(Request $request)
    {

        $input = $request->all();
        $joeysList = unserialize(base64_decode($input['joeys']));
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $montreal_joeys = [];
        $otawa_joeys = [];
        $toronto_joeys = [];
        if (in_array('477260', $vendors)) { // montreal
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->whereIn('joey_id', $joeysList)->where('deleted_at', null)->where('creator_id', 477260)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('creator_id', 477260)->pluck('joey_id');
            if (!empty($query)) {
                $montreal_joeys = ($query);
            }
        }
        if (in_array('477282', $vendors)) { // otawa
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->whereIn('joey_id', $joeysList)->where('deleted_at', null)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->pluck('joey_id');
            if (!empty($query)) {
                $otawa_joeys = ($query);
            }
        }
        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) { // toronto OR ctc
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = CTCEntry::whereIn('joey_id', $joeysList)->whereIn('creator_id', $ctc_ids)->where('deleted_at', null)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->pluck('joey_id');
            if (!empty($query)) {
                $toronto_joeys = ($query);
            }
        }

        $allJoeys = array_merge($montreal_joeys, $otawa_joeys, $toronto_joeys);
        if (isset($allJoeys)) {
            $allJoeys = array_unique($allJoeys);
        }
        $return['joeys_id'] = base64_encode(serialize($allJoeys));
        $return['joeys_count'] = count($allJoeys);
        $return['joeys_count'] = 105;

        return $return;


    }

    public function getAllOrderCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $montreal_count = [
            'total' => 0,
            'delivered_order' => 0,
        ];
        $ottawa_count = [
            'total' => 0,
            'delivered_order' => 0,
        ];
        $ctc_count = [
            'total' => 0,
            'delivered_order' => 0,
        ];

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477260])->where('is_custom_route', 0)->pluck('task_id');
            $amazon = new AmazonEnteries();
            $montreal_count = $amazon->getAmazonCounts($taskIds, 'all');

        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->where('is_custom_route', 0)->pluck('task_id');
            $amazon = new AmazonEnteries();
            $ottawa_count = $amazon->getAmazonCounts($taskIds, 'all');
        }

        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->pluck('task_id');
            $ctc = new CTCEntry();
            $ctc_count = $ctc->getCtcCounts($taskIds, 'all');
        }
        //$counts['total'] = $montreal_count['total'] + $ottawa_count['total'] + $ctc_count['total'];
        //$counts['delivered_order'] = $montreal_count['delivered_order'] + $ottawa_count['delivered_order'] + $ctc_count['delivered_order'];

        $counts['total'] = 570;
        $counts['delivered_order'] = 339;

        return $counts;
    }

    public function getJoeyManagementOtdDay(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

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
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
                ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if ($totalcount == 0) {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round((339 / 570) * 100, 2), 'y2' => round(100 - ((339 / 570) * 100), 2), 'ontime' => 570 - 55, 'offtime' => 55];
        $odt_data_1 = ['y1' => 1.9, 'y2' => 98.91, 'ontime' => 3190, 'offtime' => 35];
        return $odt_data_1;
    }

    public function getJoeyManagementOtdWeek(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-6 day', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
            if (in_array('477260', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
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
            }

            if (in_array('477282', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
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
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
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
            }
        }

        if ($totalcount == 0) {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round((890 / 1020) * 100, 2), 'y2' => round(100 - ((890 / 1020) * 100), 2), 'ontime' => 1020 - 45, 'offtime' => 45];

        $odt_data_1 = ['y1' => 2.54, 'y2' => 97.46, 'ontime' => 9640, 'offtime' => 251];
        return $odt_data_1;
    }

    public function getJoeyManagementOtdMonth(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();

        $totalcount = 0;
        $totallates = 0;

        $all_dates = [];
        $range_from_date = new Carbon(date('Y-m-d', strtotime('-1 month', strtotime($date))));
        $range_to_date = new Carbon($date);
        while ($range_from_date->lte($range_to_date)) {
            $all_dates[] = $range_from_date->toDateString();
            $range_from_date->addDay();
        }
        foreach ($all_dates as $range_date) {
            if (in_array('477260', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
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
            }

            if (in_array('477282', $vendors)) {
                $amazon_date = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
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
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $sprint_id = SprintTaskHistory:: where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
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
            }
        }

        if ($totalcount == 0) {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round((450 / 2130) * 100, 2), 'y2' => round(100 - ((150 / 2130) * 100), 2), 'ontime' => 2130 - 35, 'offtime' => 35];
        $odt_data_1 = ['y1' => 2.82, 'y2' => 97.18, 'ontime' => 21540, 'offtime' => 624];

        return $odt_data_1;
    }

    public function getAllJoeysList(Datatables $datatables, Request $request)
    {
        $data = $request->all();

        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($data['hub_id']) ? $data['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);

        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $montreal_joeys = [];
        $otawa_joeys = [];
        $toronto_joeys = [];
        $montreal_onduty_joeys = [];
        $ottawa_onduty_joeys = [];
        $toronto_onduty_joeys = [];
        if (in_array('477260', $vendors)) { // montreal 16
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $montreal_joeys = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 16)->pluck('joey_id')->toArray();
            $montreal_onduty_joeys = DB::table('amazon_enteries')->whereIn('joey_id', $montreal_joeys)->where('deleted_at', null)->where('creator_id', 477260)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('creator_id', 477260)->pluck('joey_id');
        }
        if (in_array('477282', $vendors)) { // otawa 19
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $otawa_joeys = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 19)->pluck('joey_id')->toArray();
            $ottawa_onduty_joeys = DB::table('amazon_enteries')->whereIn('joey_id', $otawa_joeys)->where('deleted_at', null)->where('creator_id', 477260)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('creator_id', 477282)->pluck('joey_id');

        }
        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) { // toronto OR ctc 17
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $toronto_joeys = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 17)->pluck('joey_id')->toArray();
            $toronto_onduty_joeys = CTCEntry::whereIn('joey_id', $toronto_joeys)->whereIn('creator_id', $ctc_ids)->where('deleted_at', null)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->pluck('joey_id');

        }
        $allJoeys = array_merge($montreal_joeys, $otawa_joeys, $toronto_joeys);

        $ondutyallJoeys = array_merge($montreal_onduty_joeys, $ottawa_onduty_joeys, $toronto_onduty_joeys);
        $offduty = array_diff($allJoeys, $ondutyallJoeys);
        $joeys = Joey::where('is_active', 0)->whereNull('deleted_at');

        if ($data['type'] == 'totaljoeys') {
            $joeys->whereIn('id', $allJoeys);
        } elseif ($data['type'] == 'joeyson') {
            $joeys->whereIn('id', $ondutyallJoeys);
        } elseif ($data['type'] == 'joeysoff') {
            $joeys->whereIn('id', $offduty);
        }

        return $datatables->eloquent($joeys)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) use ($hub_id) {
                $joey_id = base64_encode($record->id);
                $datepick = date('Y-m-d');
                $first_name = $record->first_name;
                return backend_view('statistics.joey_action', compact('first_name', 'datepick', 'hub_id', 'joey_id'));
            })
            ->make(true);
    }

    public function getAllOrderList(Datatables $datatables, Request $request)
    {
        $data = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($data['hub_id']) ? $data['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);

        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $mont_taskIds = [];
        $ott_taskIds = [];
        $ctc_taskIds = [];
        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $mont_taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477260])->where('is_custom_route', 0)->pluck('task_id');
        }

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $ott_taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->where('is_custom_route', 0)->pluck('task_id');
        }

        if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $ctc_taskIds = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->pluck('task_id');
        }

        $ctc_taskIds = array_merge($mont_taskIds, $ott_taskIds, $ctc_taskIds);
        $mont_date = collect([]);
        $ctc_date = collect([]);
        if ($data['type'] == 'totalorders') {
            if ($this->in_array_any(['477260', '477282'], $vendors)) {
                $mont_date = collect(AmazonEnteries::whereIn('task_id', $mont_taskIds)->where('is_custom_route', 0));
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = collect(CTCEntry::table('ctc_entries')->whereIn('creator_id', $mont_taskIds));
            }
        } elseif ($data['type'] == 'ordersdelivered') {
            if ($this->in_array_any(['477260', '477282'], $vendors)) {
                $mont_date = AmazonEnteries::whereIn('task_id', $mont_taskIds)->where('is_custom_route', 0)->whereIn('task_status_id', $this->getStatusCodes('competed'));
            }

            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date = CTCEntry::whereIn('creator_id', $mont_taskIds)->whereIn('task_status_id', $this->getStatusCodes('competed'));
            }
        }

        $allOrder = $mont_date->marge($ctc_date);
        return $datatables->eloquent($allOrder)
            ->make(true);
    }

    public function in_array_any($needles, $haystack)
    {
        return !empty(array_intersect($needles, $haystack));
    }

    public function getAllJoeysOTD(Request $request)
    {
        $input = $request->all();
        $type = $input['type'];
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $joeyList = [];
        $joeyTrackingListList = [];
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


        foreach ($all_dates as $range_date) {

            if (in_array('477260', $vendors)) {
                $joey_ids = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 16)->pluck('joey_id')->toArray();
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = DB::table('amazon_enteries')->whereIn('joey_id', $joey_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477260])->get(['tracking_id', 'joey_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at)) {
                                if ($record->joey_id) {
                                    if ($record->delivered_at > $range_date . " 21:00:00") {
                                        if (!isset($joeyList[$record->joey_id])) {
                                            $joeyList[$record->joey_id] = 1;
                                        } else {
                                            $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                        }
                                    }
                                }
                                //$joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                            }
                        }
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at)) {
                                if ($record->joey_id) {
                                    if ($record->returned_at > $range_date . " 21:00:00") {
                                        if (!isset($joeyList[$record->joey_id])) {
                                            $joeyList[$record->joey_id] = 1;
                                        } else {
                                            $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                        }
                                    }
                                   // $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                                }
                            }
                        }
                        $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                    }
                }
            }
            if (in_array('477282', $vendors)) {
                $joey_ids = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 19)->pluck('joey_id')->toArray();
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = DB::table('amazon_enteries')->whereIn('joey_id', $joey_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                    ->where(['creator_id' => 477282])->get(['tracking_id', 'joey_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                        \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at)) {
                                if ($record->joey_id) {
                                    if ($record->delivered_at > $range_date . " 21:00:00") {
                                        if (!isset($joeyList[$record->joey_id])) {
                                            $joeyList[$record->joey_id] = 1;
                                        } else {
                                            $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                        }
                                    }
                                    //$joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                                }
                            }
                        }
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at)) {
                                if ($record->joey_id) {
                                    if ($record->returned_at > $range_date . " 21:00:00") {
                                        if (!isset($joeyList[$record->joey_id])) {
                                            $joeyList[$record->joey_id] = 1;
                                        } else {
                                            $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                        }
                                    }
                                   // $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                                }
                            }
                        }
                        $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                    }
                }
            }
            if (count(array_intersect($ctcVendorIds, $vendors)) > 0) {
                $ctc_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $joey_ids = JoeyRoutes::where('deleted_at', null)->whereNotNull('joey_id')->where('hub', 17)->pluck('joey_id')->toArray();
                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('joey_id', $joey_ids)->whereIn('sprint_id', $sprint_id)->get(['tracking_id', 'joey_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!is_null($record->delivered_at)) {
                                if ($record->joey_id) {
                                    if ($record->delivered_at > $range_date . " 21:00:00") {
                                        if (!isset($joeyList[$record->joey_id])) {
                                            $joeyList[$record->joey_id] = 1;
                                        } else {
                                            $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                        }
                                    }
                                   // $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                                }
                            }
                        }
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                            if (!is_null($record->returned_at)) {
                                if ($record->joey_id) {
                                    if ($record->returned_at > $range_date . " 21:00:00") {
                                        if (!isset($joeyList[$record->joey_id])) {
                                            $joeyList[$record->joey_id] = 1;
                                        } else {
                                            $joeyList[$record->joey_id] = $joeyList[$record->joey_id] + 1;
                                        }
                                    }
                                   // $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                                }
                            }
                        }
                        $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
                    }
                }
            }
        }
        arsort($joeyList);

        $finalList = [];
        $i = 0;
        foreach ($joeyList as $key => $value) {
            $total = count($joeyTrackingListList[$key]);
            $otd = round(100 - (($value / $total) * 100), 2);

            $joey = Joey::where('id', $key)->first();
            $finalList[$i]['name'] = '<a href="' . url('/') . '/statistics/joey-detail?datepicker=' . $date . '&hub=$' . $hub_id . '&rec=' . base64_encode($joey->id) . '" target="_blank">' . $joey->first_name . ' ' . $joey->last_name . '</a>';//$joey->first_name . " " . $joey->last_name;
            $finalList[$i]['image'] = $joey->image != null ? $joey->image : url('/') . '/images/profile_images/default.png';
            $finalList[$i]['count'] = $otd . '%';
            $finalList[$i]['joey_id'] = $joey->id;
            $finalList[$i]['encode_joey_id'] = base64_encode($joey->id);
            $finalList[$i]['date'] = $date;
            $finalList[$i]['email'] = $joey->email;
            $finalList[$i]['phone'] = $joey->phone;
            $finalList[$i]['address'] = $joey->address ? $joey->address : '';
            $i++;
        }
            /*$deliverytime = [];
            $ststausList = array_merge($sprint->getStatusCodes('competed'), $sprint->getStatusCodes('return'));
            foreach ($joeyList as $key => $value) {
                $sort_task_ids = MerchantIds::whereIn('tracking_id', $joeyTrackingListList[$key])->pluck('task_id')->toArray();
                $first_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id', $sort_task_ids)->whereIn('status_id', $ststausList)->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->orderBy('created_at', 'ASC')->first();
                $last_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id', $sort_task_ids)->whereIn('status_id', $ststausList)->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                    ->orderBy('created_at', 'DESC')->first();
                $drop_hour = 0;
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
                } else {
                    $deliverytime[$key] = 0;
                }
            }
            arsort($deliverytime);


            $finalList = [];
            $i = 0;
            foreach ($deliverytime as $key => $value) {
                $joey = Joey::where('id', $key)->first();
                $finalList[$i]['name'] = '<a href="'.url('/').'/statistics/joey-detail?datepicker='.$date.'&hub=$'.$hub_id.'&rec='.base64_encode($joey->id).'" target="_blank">'.$joey->first_name .' '. $joey->last_name.'</a>';//$joey->first_name . " " . $joey->last_name;

                $finalList[$i]['image'] = $joey->image != null ? $joey->image : url('/') . '/images/profile_images/default.png';
                $finalList[$i]['count'] = $joeyList[$key];
                $finalList[$i]['joey_id'] = $joey->id;
                $finalList[$i]['encode_joey_id'] = base64_encode($joey->id);
                $finalList[$i]['date'] = $date;
                $finalList[$i]['email'] = $joey->email;
                $finalList[$i]['phone'] = $joey->phone;
                $finalList[$i]['address'] = $joey->address?$joey->address:'';
                $i++;
            }*/
        return $finalList;
    }


}
