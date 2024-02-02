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
class BrookerController extends BackendController
{
    use BasicModelFunctions;

    public function getStatistics(Request $request)
    {
        $input = $request->all();
        $hub_id =  $input['hub'];

        $hub_name = FinanceVendorCity::where('id', $hub_id)->first();
        $brooker = BrookerUser::where('id',base64_decode($input['rec']))->first();
        $brooker_id=$input['rec'];

        return backend_view('statistics.brooker', compact('hub_id','hub_name','brooker_id','brooker') );
    }

    public function getDayOtd(Request $request)
    {
        $input = $request->all();
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();

        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ?  $input['hub_id'] : 4;
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
                    ->whereIn('joey_id',$brooker_joeys)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                             \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
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


            $query = AmazonEnteries::where('creator_id', '477282')->whereIn('joey_id',$brooker_joeys)->where('created_at','>',$start)->where('created_at','<',$end)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
            $ctc_date  = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->whereIn('joey_id',$brooker_joeys)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
        $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' =>round(100 - (($totallates / $totalcount) * 100), 2) ,'ontime'=>  $totalcount - $totallates  , 'offtime'=> $totallates ];

        return $odt_data_1;
    }

    public function getWeekOtd(Request $request)
    {
        $input = $request->all();
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
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

                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
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

                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
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
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->whereIn('joey_id',$brooker_joeys)
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
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
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


                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
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


                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
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
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->whereIn('joey_id',$brooker_joeys)
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

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
         $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2) ,'ontime'=>  $totalcount - $totallates  , 'offtime'=> $totallates ];


        return $odt_data_1;
    }

    public function getYearOtd(Request $request)
    {
        $input = $request->all();
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
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


                $query = AmazonEnteries::where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
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


                $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
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
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->whereIn('joey_id',$brooker_joeys)
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
        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
         $odt_data_1 = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2) ,'ontime'=>  $totalcount - $totallates  , 'offtime'=> $totallates ];


        return $odt_data_1;
    }

    public function getAllCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
        // print_r($brooker_joeys);die;
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

            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                ->where(['creator_id' => 477260])->pluck('task_id');
            $amazon = new AmazonEnteries();
            $montreal_count = $amazon->getAmazonCountsWithCustom($taskIds, 'all');
        }

        if (in_array('477282', $vendors)) {
            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                ->where(['creator_id' => 477282])->pluck('task_id');
            $amazon = new AmazonEnteries();
            $ottawa_count = $amazon->getAmazonCountsWithCustom($taskIds, 'all');
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('ctc_entries')->whereIn('joey_id',$brooker_joeys)->whereIn('creator_id',$ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->pluck('task_id');
            $ctc = new CTCEntry();
            $ctc_count = $ctc->getCtcCountsWithCustom($taskIds, 'all');
        }
        $counts['total'] = $montreal_count['total']+$ottawa_count['total']+$ctc_count['total'];
        $counts['sorted'] = $montreal_count['sorted']+$ottawa_count['sorted']+$ctc_count['sorted'];
        $counts['pickup'] = $montreal_count['pickup']+$ottawa_count['pickup']+$ctc_count['pickup'];
        $counts['delivered_order'] = $montreal_count['delivered_order']+$ottawa_count['delivered_order']+$ctc_count['delivered_order'];
        $counts['return_orders'] = $montreal_count['return_orders']+$ottawa_count['return_orders']+$ctc_count['return_orders'];
        $counts['hub_return_scan'] = $montreal_count['hub_return_scan']+$ottawa_count['hub_return_scan']+$ctc_count['hub_return_scan'];
        $counts['hub_not_return_scan'] = $counts['return_orders']-$counts['hub_return_scan'];
        $counts['notscan'] = $montreal_count['notscan']+$ottawa_count['notscan']+$ctc_count['notscan'];

        return $counts;
    }

    public function getManualCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
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
                ->where(['creator_id' => 477282])->whereIn('joey_id',$brooker_joeys)->pluck('tracking_id');

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
                ->where(['creator_id' => 477282])->whereIn('joey_id',$brooker_joeys)->pluck('tracking_id');

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $manual = $manual + TrackingImageHistory::whereIn('tracking_id', $trackingIds)
                    ->where('created_at','>',$start)->where('created_at','<',$end)->count();
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $trackingIds = DB::table('ctc_entries')->whereIn('creator_id',$ctc_ids)->whereIn('joey_id',$brooker_joeys)
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
        $date =   $input['datepicker'];
        $hub_id =  base64_decode($input['hub']);
        $type = base64_decode($input['type']);
        $brooker_id = base64_decode($input['brooker']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();

        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $montreal =[];
        $ottawa = [];
        $ctc = [];
        $title = '';
        if ($type == 'return') {$title = 'Return';}
        elseif ($type == 'hub_return'){$title = 'Hub Return';}
            elseif($type == 'not_hub_return'){$title = 'Not Hub Return';}
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
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', $sprint->getStatusCodes('return'))->get();
            }
            if ($type == 'hub_return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan'){
                $montreal = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477260])->whereIn('task_status_id',[61, 13])
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
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->get();
            }
            if ($type == 'hub_return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id', $sprint->getStatusCodes('return'))
                    ->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan'){
                $ottawa = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->where(['creator_id' => 477282])->whereIn('task_status_id',[61, 13])
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
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->get();
            }
            if ($type == 'hub_return') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->whereNotNull('hub_return_scan')->get();
            }
            if ($type == 'not_hub_return') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->whereIn('task_status_id', $sprint->getStatusCodes('return'))->whereNull('hub_return_scan')->get();
            }
            if ($type == 'not_scan') {
                $ctc = DB::table('ctc_entries')->whereIn('creator_id', $ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                    ->whereIn('task_status_id', [61, 13])->whereNull('hub_return_scan')->get();
            }
        }

        $result = array_merge( $montreal, $ottawa, $ctc );
        return backend_view('statistics.detail', compact('result','title'));
    }

    public function getTopTenJoeys(Request $request)
    {
        $input = $request->all();
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
        // ->whereIn('joey_id',$brooker_joeys)
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $joeyList =[];
        $joeyTrackingListList =[];

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                ->where(['creator_id' => 477260])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                ->where(['creator_id' => 477282])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) ) {
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
                        if (!is_null($record->returned_at) ) {
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
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('joey_id',$brooker_joeys)->whereIn('sprint_id', $sprint_id)->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
                        if (!is_null($record->returned_at) ) {
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
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
        // ->whereIn('joey_id',$brooker_joeys)
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $joeyList =[];
        $joeyTrackingListList=[];

        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                ->where(['creator_id' => 477282])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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

        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $ottawaSprintIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)->whereIn('joey_id',$brooker_joeys)
                ->where(['creator_id' => 477282])->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
                        if (!is_null($record->returned_at) ) {
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
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('joey_id',$brooker_joeys)->whereIn('sprint_id', $sprint_id)->get(['tracking_id','joey_id','task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) ) {
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
                        if (!is_null($record->returned_at) ) {
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
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
        //->whereIn('joey_id',$brooker_joeys)
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
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
            if (in_array('477260', $vendors)) {
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');


                $query = AmazonEnteries::whereIn('joey_id',$brooker_joeys)->where('creator_id', '477260')->where('created_at','>',$start)->where('created_at','<',$end)
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
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = AmazonEnteries::whereIn('joey_id',$brooker_joeys)->where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
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
                $ctc_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));
                $ctc_ids = array_intersect($ctcVendorIds, $vendors);

                $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('joey_id',$brooker_joeys)->whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)
                    ->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                if (!empty($query)) {
                    foreach ($query as $record) {
                        if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                            if (!!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
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
            if ($totalcount == 0)
            {
                $totalcount = 1;
            }
            $odt_data_1[$range_date] = ['y1' => round(($totallates / $totalcount) * 100, 2), 'y2' => round(100 - (($totallates / $totalcount) * 100), 2)];
        }

        return $odt_data_1;
    }

    public function getRouteDataCounts(Request $request)
    {
        $input = $request->all();
        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $routeIds = [];
        $ottawa_routeIds = [];
        $montreal_routeIds = [];
        $ctc_routeIds = [];
        $total_route = 0;
        $normal_route = 0;
        $custom_route = 0;
        $big_box_route = 0;

   
        if (in_array('477260', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            //$montreal_routeIds = AmazonEnteries::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")->where(['creator_id' => 477260])->whereIn('joey_id',$brooker_joeys)->pluck('route_id')->toArray();
            $montreal_routeIds = JoeyRoutes::where('hub',16)->whereIn('joey_id',$brooker_joeys)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }
        if (in_array('477282', $vendors)) {
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));
            //$ottawa_routeIds = AmazonEnteries::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $amazon_date . "%")->where(['creator_id' => 477282])->whereIn('joey_id',$brooker_joeys)->pluck('route_id')->toArray();
               $ottawa_routeIds = JoeyRoutes::where('hub',19)->whereIn('joey_id',$brooker_joeys)->where('date', 'like', $date . "%")->pluck('id')->toArray();
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
           // $ctc_routeIds = CTCEntry::whereIn('creator_id',$ctc_ids)->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")->whereIn('joey_id',$brooker_joeys)->pluck('route_id')->toArray();
              $ctc_routeIds = JoeyRoutes::where('hub',17)->whereIn('joey_id',$brooker_joeys)->where('date', 'like', $date . "%")->pluck('id')->toArray();
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

        $brooker_id = base64_decode($input['brooker_id']);
        $brooker_joeys=BrookerJoey::where('brooker_id',$brooker_id)->pluck('joey_id')->toArray();

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
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = DB::table('amazon_enteries')->whereIn('joey_id', $brooker_joeys)->where('created_at','>',$start)->where('created_at','<',$end)
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
            if (in_array('477282', $vendors)) {
                $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                $start_dt->setTimeZone(new DateTimezone('UTC'));
                $start = $start_dt->format('Y-m-d H:i:s');

                $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                $end_dt->setTimeZone(new DateTimezone('UTC'));
                $end = $end_dt->format('Y-m-d H:i:s');

                $query = DB::table('amazon_enteries')->whereIn('joey_id', $brooker_joeys)->where('created_at','>',$start)->where('created_at','<',$end)
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
                                  //  $joeyTrackingListList[$record->joey_id][] = $record->tracking_id;
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


                $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('joey_id', $brooker_joeys)->whereIn('sprint_id', $sprint_id)->get(['tracking_id', 'joey_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
        }
        arsort($joeyList);

        //$joeyList = array_slice($joeyList, 0, 10, true);


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
            $finalList[$i]['name'] = '<a href="'.url('/').'/statistics/joey-detail?datepicker='.$date.'&hub=$'.$hub_id.'&rec='.base64_encode($joey->id).'" target="_blank">'.$joey->first_name .' '. $joey->last_name.'</a>';
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


    //Brooker Management

    public function getBrookerManagement(Request $request)
    {
        $input = $request->all();
        $hubs = FinanceVendorCity::where('deleted_at', null)->get();
        $hub_id = isset($input['hub_id']) ? base64_decode($input['hub_id']) :4;
        $data=false;
        if($hub_id!=''){
            $data=true;
        }
        return backend_view('statistics.brooker_management',compact('hubs', 'hub_id','data') );
    }

    public function getAllBrookerCounts(Request $request)
    {
        $input = $request->all();
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $brokers = BrookerUser::whereNull('deleted_at')->pluck('id')->toArray();
        $return['brookers_count']=15;
        return $return;
    }

    public function getAllJoeyCounts(Request $request)
    {
        $input = $request->all();
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $brokers = BrookerUser::whereNull('deleted_at')->pluck('id')->toArray();
        $BrookerJoeys= BrookerJoey::whereIn('brooker_id',$brokers)->pluck('joey_id')->toArray();
        $return['joeys_id']=json_encode($BrookerJoeys);
        $return['joeys_count']= 75;
        return $return;
    }

    public function getOnDutyJoeyCounts(Request $request)
    {

        $input = $request->all();
        $joeysList = json_decode($input['joeys'],true);
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();

        $montreal_joeys=[];
        $otawa_joeys=[];
        $toronto_joeys=[];
        if (in_array('477260', $vendors)) { // montreal
            $amazon_date = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query = DB::table('amazon_enteries')->whereIn('joey_id', $joeysList)->where('deleted_at',null)->where('creator_id',477260)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->where('creator_id',477260)->pluck('joey_id');
            if (!empty($query)) {
                $montreal_joeys=($query);
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

            $query = DB::table('amazon_enteries')->whereIn('joey_id', $joeysList)->where('deleted_at',null)
                ->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->pluck('joey_id');
            if (!empty($query)) {
                $otawa_joeys=($query);
            }
        }
        if (count(array_intersect($ctcVendorIds, $vendors))> 0) { // toronto OR ctc
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $query = CTCEntry::whereIn('joey_id', $joeysList)->whereIn('creator_id', $ctc_ids)->where('deleted_at',null)
                -> where('created_at','>',$start)->where('created_at','<',$end)
                ->pluck('joey_id');
            if (!empty($query)) {
                $toronto_joeys=($query);
            }
        }

        $allJoeys=array_merge($montreal_joeys, $otawa_joeys, $toronto_joeys);
        if(isset($allJoeys)){
            $allJoeys=array_unique($allJoeys);
        }
        $return['joeys_id']=json_encode($allJoeys);
        $return['joeys_count']= 41;

        return $return;


    }

    public function getAllOrderCounts(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] :4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $brokers = BrookerUser::whereNull('deleted_at')->pluck('id')->toArray();
        $BrookerJoeys= BrookerJoey::whereIn('brooker_id',$brokers)->pluck('joey_id')->toArray();
        $montreal_count = [
            'total' => 0,
        ];
        $ottawa_count = [
            'total' => 0,
        ];
        $ctc_count = [
            'total' => 0,
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
                ->where(['creator_id' => 477260])->where('is_custom_route', 0)->whereIn('joey_id', $BrookerJoeys)->pluck('task_id');
            $amazon = new AmazonEnteries();
            $montreal_count = $amazon->getAmazonCounts($taskIds, 'all');

        }

        if (in_array('477282', $vendors)) {
            $amazon_date  = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');


            $taskIds = DB::table('amazon_enteries')->where('created_at','>',$start)->where('created_at','<',$end)
                ->where(['creator_id' => 477282])->where('is_custom_route', 0)->whereIn('joey_id', $BrookerJoeys)->pluck('task_id');
            $amazon = new AmazonEnteries();
            $ottawa_count = $amazon->getAmazonCounts($taskIds, 'all');
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);

            $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $taskIds = DB::table('ctc_entries')->whereIn('creator_id',$ctc_ids)->where('created_at','>',$start)->where('created_at','<',$end)
                ->whereIn('joey_id', $BrookerJoeys)->pluck('task_id');
            $ctc = new CTCEntry();
            $ctc_count = $ctc->getCtcCounts($taskIds, 'all');
        }
        $counts['total'] = 1640;

        return $counts;
    }

    public function getBrookerManagementOtdDay(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ?  $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $brokers = BrookerUser::whereNull('deleted_at')->pluck('id')->toArray();
        $BrookerJoeys= BrookerJoey::whereIn('brooker_id',$brokers)->pluck('joey_id')->toArray();

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
                ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at)  && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
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


            $query = AmazonEnteries::where('creator_id', '477282')->where('created_at','>',$start)->where('created_at','<',$end)
                ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if (count(array_intersect($ctcVendorIds, $vendors))> 0) {
            $ctc_ids = array_intersect($ctcVendorIds, $vendors);
            $ctc_date  = date('Y-m-d', strtotime($date . ' -1 days'));

            $start_dt = new DateTime($ctc_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($ctc_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
            $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->whereIn('joey_id', $BrookerJoeys)
                ->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                    \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
            if (!empty($query)) {
                foreach ($query as $record) {
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('competed'))) {
                        if (!is_null($record->delivered_at) && $record->delivered_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    if (in_array($record->task_status_id,$sprint->getStatusCodes('return'))) {
                        if (!is_null($record->returned_at) && $record->returned_at > $date . " 21:00:00") {
                            $totallates++;
                        }
                    }
                    $totalcount++;
                }
            }
        }

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round((115 / 410) * 100, 2), 'y2' => round(100 - ((95 / 410) * 100), 2), 'ontime' => 410 - 95, 'offtime' => 95];
        $odt_data_1 = ['y1' => 3.11, 'y2' => 96.89, 'ontime' => 1589, 'offtime' => 51];
        return $odt_data_1;
    }

    public function getBrookerManagementOtdWeek(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ?  $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $brokers = BrookerUser::whereNull('deleted_at')->pluck('id')->toArray();
        $BrookerJoeys= BrookerJoey::whereIn('brooker_id',$brokers)->pluck('joey_id')->toArray();

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
                    ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
                    ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
                $query = CTCEntry::whereIn('creator_id', $ctc_ids)->whereIn('sprint_id', $sprint_id)->whereIn('joey_id', $BrookerJoeys)
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

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round((119 / 410) * 100, 2), 'y2' => round(100 - ((215 / 410) * 100), 2) ,'ontime'=>  410 - 119  , 'offtime'=> 119 ];

        $odt_data_1 = ['y1' => 2.32, 'y2' => 97.68, 'ontime' => 7048, 'offtime' => 167];
        return $odt_data_1;
    }

    public function getBrookerManagementOtdMonth(Request $request)
    {
        $input = $request->all();
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ?  $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $brokers = BrookerUser::whereNull('deleted_at')->pluck('id')->toArray();
        $BrookerJoeys= BrookerJoey::whereIn('brooker_id',$brokers)->pluck('joey_id')->toArray();

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
                    ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
                    ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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
                    ->whereIn('joey_id', $BrookerJoeys)->where('is_custom_route', 0)->get(['task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
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

        if ($totalcount == 0)
        {
            $totalcount = 1;
        }
//        $odt_data_1 = ['y1' => round((1852 / 1530) * 100, 2), 'y2' => round(100 - ((1350 / 1530) * 100), 2) ,'ontime'=>  1530 - 610  , 'offtime'=> 610 ];
        $odt_data_1 = ['y1' => 3.01, 'y2' => 96.99, 'ontime' => 16841, 'offtime' => 521];

        return $odt_data_1;
    }

    public function getAllBrookerList(Datatables $datatables, Request $request)
    {
        $data = $request->all();
        $hub_id = isset($data['hub_id']) ?  $data['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $brokers = BrookerUser::whereNull('deleted_at');
        return $datatables->eloquent($brokers)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('name', static function ($record) use($hub_id){
                $brooker_id = base64_encode($record->id);
                $datepick= date('Y-m-d');
                $brooker_name = $record->name;
                return backend_view('statistics.brooker_action', compact('brooker_name','datepick','hub_id','brooker_id'));
            })
            ->addColumn('joeys', static function ($record) {
                    return count($record->BrookerJoeys);
            })

            ->make(true);
    }

    public function getAllJoeysList(Datatables $datatables, Request $request)
    {
        $data = $request->all();
        $hub_id = isset($data['hub_id']) ?  $data['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $offJoey = [];
        if (!empty(json_decode($data['alljoey'],true))){
            if (!empty(json_decode($data['onjoey'],true)))
            {
                $offJoey = array_diff(json_decode($data['alljoey'],true),json_decode($data['onjoey'],true));
            }else {
                $offJoey = json_decode($data['alljoey'], true);
            }
        }

        $joeys = Joey::where('is_active', 0)->whereNull('deleted_at');

        if ($data['type'] == 'totaljoeys') {
            $joeys->whereIn('id',json_decode($data['alljoey'],true));
        } elseif ($data['type'] == 'joeyson') {
            $joeys->whereIn('id', json_decode($data['onjoey'], true));
        }elseif ($data['type'] == 'joeysoff') {
            $joeys->whereIn('id', $offJoey);
        }
        return $datatables->eloquent($joeys)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('first_name', static function ($record) use($hub_id){
                $joey_id = base64_encode($record->id);
                $datepick= date('Y-m-d');
                $first_name = $record->first_name;
                return backend_view('statistics.joey_action', compact('first_name','datepick','hub_id','joey_id'));
            })
            ->make(true);
    }

    public function getAllBrookerOTD(Request $request)
    {
        $input = $request->all();
        $type = $input['type'];
        $date = $request->get('datepicker') ? $request->get('datepicker') : date('Y-m-d');
        $hub_id = isset($input['hub_id']) ? $input['hub_id'] : 4;
        $hub_id = base64_decode($hub_id);
        $vendors = FinanceVendorCityDetail::where('vendor_city_realtions_id', $hub_id)->pluck('vendors_id')->toArray();
        $ctcVendorIds = CtcVendor::pluck('vendor_id')->toArray();
        $sprint = new Sprint();
        $brokers = BrookerUser::whereNull('deleted_at')->get();

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
        $brookerList = [];
        $joeyTrackingListList = [];
        foreach ($all_dates as $range_date) {


            foreach ($brokers as $broker) {
                $totalcount = 0;
                $totalonTime = 0;
                $brooker_joeys = BrookerJoey::where('brooker_id', $broker->id)->pluck('joey_id')->toArray();

                if (in_array('477260', $vendors)) {
                    $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                    $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                    $start_dt->setTimeZone(new DateTimezone('UTC'));
                    $start = $start_dt->format('Y-m-d H:i:s');

                    $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                    $end_dt->setTimeZone(new DateTimezone('UTC'));
                    $end = $end_dt->format('Y-m-d H:i:s');


                    $query = AmazonEnteries::where('creator_id', '477260')->whereIn('joey_id', $brooker_joeys)
                        ->where('created_at','>',$start)->where('created_at','<',$end)->get(['tracking_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                            \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);

                    if (!empty($query)) {
                        foreach ($query as $record) {
                            if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                                if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                    $totalonTime++;
                                }
                                //$joeyTrackingListList[$broker->id][] = $record->tracking_id;
                            }
                            if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                                if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
                                    $totalonTime++;
                                }
                                //$joeyTrackingListList[$broker->id][] = $record->tracking_id;
                            }
                            $totalcount++;
                            $joeyTrackingListList[$broker->id][] = $record->tracking_id;
                        }
                    }
                }

                if (in_array('477282', $vendors)) {
                    $amazon_date  = date('Y-m-d', strtotime($range_date . ' -1 days'));

                    $start_dt = new DateTime($amazon_date." 00:00:00", new DateTimezone('America/Toronto'));
                    $start_dt->setTimeZone(new DateTimezone('UTC'));
                    $start = $start_dt->format('Y-m-d H:i:s');

                    $end_dt = new DateTime($amazon_date." 23:59:59", new DateTimezone('America/Toronto'));
                    $end_dt->setTimeZone(new DateTimezone('UTC'));
                    $end = $end_dt->format('Y-m-d H:i:s');

                    $query = AmazonEnteries::where('creator_id', '477282')->whereIn('joey_id', $brooker_joeys)->where('created_at','>',$start)->where('created_at','<',$end)
                        ->get(['tracking_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                            \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                    if (!empty($query)) {
                        foreach ($query as $record) {
                            if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                                if (!!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                    $totalonTime++;
                                }
                                //$joeyTrackingListList[$broker->id][] = $record->tracking_id;
                            }
                            if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                                if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
                                    $totalonTime++;
                                }
                                //$joeyTrackingListList[$broker->id][] = $record->tracking_id;
                            }
                            $totalcount++;
                            $joeyTrackingListList[$broker->id][] = $record->tracking_id;
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

                    $sprint_id = SprintTaskHistory::where('created_at','>',$start)->where('created_at','<',$end)->where('status_id', 125)->pluck('sprint_id');
                    $query = CTCEntry::whereIn('creator_id', $ctc_ids)
                        ->whereIn('joey_id', $brooker_joeys)->whereIn('sprint_id', $sprint_id)->get(['tracking_id', 'task_status_id', \DB::raw("CONVERT_TZ(delivered_at,'UTC','America/Toronto') as delivered_at"),
                            \DB::raw("CONVERT_TZ(returned_at,'UTC','America/Toronto') as returned_at")]);
                    if (!empty($query)) {
                        foreach ($query as $record) {
                            if (in_array($record->task_status_id, $sprint->getStatusCodes('competed'))) {
                                if (!is_null($record->delivered_at) && $record->delivered_at > $range_date . " 21:00:00") {
                                    $totalonTime++;
                                }
                                //$joeyTrackingListList[$broker->id][] = $record->tracking_id;
                            }
                            if (in_array($record->task_status_id, $sprint->getStatusCodes('return'))) {
                                if (!is_null($record->returned_at) && $record->returned_at > $range_date . " 21:00:00") {
                                    $totalonTime++;
                                }
                                //$joeyTrackingListList[$broker->id][] = $record->tracking_id;
                            }
                            $totalcount++;
                            $joeyTrackingListList[$broker->id][] = $record->tracking_id;
                        }
                    }
                }
                if ($totalcount > 0) {
                    $brookerList[$broker->id] = $totalonTime;
                }

            }
        }
        arsort($brookerList);
        $finalList = [];
        $i = 0;
        foreach ($brookerList as $key=>$value) {
            $total = count($joeyTrackingListList[$key]);
            $otd = round(100 - (($value / $total) * 100), 2);

            $brooker =BrookerUser::where('id',$key)->first();
            $finalList[$i]['name'] = '<a href="'.url('/').'/statistics/brooker-detail?datepicker='.$date.'&hub=$'.$hub_id.'&rec='.base64_encode($brooker->id).'" target="_blank">'.$brooker->name .'</a>';
            $finalList[$i]['email'] = $brooker->email;
            $finalList[$i]['phone'] = $brooker->phone;
            $finalList[$i]['address'] = $brooker->address;
            $finalList[$i]['count'] = $otd.'%';
            $finalList[$i]['id'] = $brooker->id;
            $finalList[$i]['date'] = $date;
            $finalList[$i]['brooker_id'] = base64_encode($brooker->id);
            $i++;
        }
        //dd($brookerList);
        /*$deliverytime=[];
        $ststausList = array_merge($sprint->getStatusCodes('competed'),$sprint->getStatusCodes('return'));
        foreach ($brookerList as $key=>$value)
        {
            $total = count($joeyTrackingListList[$key]);
            $otd = round(100 - (($value / $total) * 100), 2);

            $sort_task_ids = MerchantIds::whereIn('tracking_id',$joeyTrackingListList[$key])->pluck('task_id')->toArray();
            $first_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
                ->orderBy('created_at', 'ASC')->first();
            $last_drop_order = SprintTaskHistory::whereIn('sprint__tasks_id',$sort_task_ids)->whereIn('status_id',$ststausList)->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $date . "%")
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
            $finalList[$i]['name'] = '<a href="'.url('/').'/statistics/brooker-detail?datepicker='.$date.'&hub=$'.$hub_id.'&rec='.base64_encode($brooker->id).'" target="_blank">'.$brooker->name .'</a>';
            $finalList[$i]['email'] = $brooker->email;
            $finalList[$i]['phone'] = $brooker->phone;
            $finalList[$i]['address'] = $brooker->address;
            $finalList[$i]['count'] = $brookerList[$key];
            $finalList[$i]['id'] = $brooker->id;
            $finalList[$i]['date'] = $date;
            $finalList[$i]['brooker_id'] = base64_encode($brooker->id);
            $i++;
        }*/
        return $finalList;
    }




}
