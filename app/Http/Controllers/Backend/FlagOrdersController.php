<?php

namespace App\Http\Controllers\Backend;

use App\BoradlessDashboard;
use App\Classes\Fcm;
use App\Classes\JoyFlagLoginValidationsHandler;
use App\CustomerFlagCategories;
use App\CustomerFlagCategoryValues;
use App\CustomerIncidents;
use App\FinanceVendorCity;
use App\FinancialTransactions;
use App\FlagHistory;
use App\Http\Traits\BasicModelFunctions;
use App\Joey;
use App\HubZones;
use App\JoeyRoutes;
use App\JoeyTransactions;
use App\JoyFlagLoginValidations;
use App\SprintConfirmation;
use App\Task;
use App\UserDevice;
use App\UserNotification;
use App\MerchantIds;
use App\Classes\CurlRequestSend;
use Illuminate\Http\Request;
use App\JoeyPerformanceHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;


class FlagOrdersController extends BackendController
{
    use BasicModelFunctions;

    /**
     * Get Flag Orders
     */
    public function FlagOrderList(Request $request)
    {
        $data = $request->all();
        //dd($data);
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        $user = Auth::user();

//        $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $user->statistics ;
//        $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
        $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

        $hubIds = HubZones:://whereIn('zone_id',DB::raw('select zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id = '.$data.') '))
        whereIn('zone_id', function ($query) use ($statistics_id) {
            $query->select(
                DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
            );
        })
            ->pluck('hub_id')->toArray();

        //Getting all joeys flag marked
        $all_flag_joey = FlagHistory::where('unflaged_by', 0)
            ->whereIn('hub_id', $hubIds)
            ->groupBy('joey_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->get();
        //Getting all flag marked orders
        $all_flag_mark = FlagHistory::where('unflaged_by', 0)
            ->whereIn('hub_id', $hubIds)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting all flag marked orders
        $all_un_flag_mark = FlagHistory::where('unflaged_by', '>', 0)
            ->whereIn('hub_id', $hubIds)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting all approved flagged orders
        $all_approved_flag = FlagHistory::where('is_approved', 1)
            ->whereIn('hub_id', $hubIds)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting all un-approved flag orders
        $all_un_approved_flag = FlagHistory::where('unflaged_by', 0)
            ->whereIn('hub_id', $hubIds)
            ->where('is_approved', 0)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting All Blocked Joeys By Flag
        $blocked_joeys_by_flag = JoyFlagLoginValidations::where('is_blocked', 1)
            ->whereNull('deleted_at')
            ->groupBy('joey_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->get();

        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        //dd($all_un_flag_mark);
        //dd($selectjoey);
        $all_joeys_accept_selected = Joey::where('is_enabled', '=', 1)->where('deleted_at', null)->limit(10)->get();

        return backend_view('flag-orders.index',
            compact(
                'all_joeys_accept_selected',
                'selectjoey',
                'all_flag_joey',
                'all_flag_mark',
                'all_approved_flag',
                'all_un_approved_flag',
                'all_un_flag_mark',
                'blocked_joeys_by_flag'
            )
        );
    }

    /**
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function FlagOrderListData(Datatables $datatables, Request $request)
    {
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        //$start_date = $request->start_date." 00:00:00";
        //$end_date = $request->end_date." 23:59:59";

//        $test = FinanceVendorCityDetail::with('check')
//            ->where('vendor_city_realtions_id',22)
//            ->limit(10)
//
//        ->get();
        $data = Auth::user();

//        $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $data->statistics ;
//        $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
        $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

        $hubIds = HubZones:://whereIn('zone_id',DB::raw('select zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id = '.$data.') '))
        whereIn('zone_id', function ($query) use ($statistics_id) {
            $query->select(
                DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
            );
        })
            ->pluck('hub_id')->toArray();

        $query = FlagHistory::whereNull('deleted_at')
            ->whereIn('hub_id', $hubIds)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted]);

        // filters
        if ($request->joeys != '') {
            $query->where('joey_id', $request->joeys);
        }

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->editColumn('joey_name', static function ($record) {

                return $record->joeyName->FullName;
            })
            ->editColumn('flag_by', static function ($record) {
                return $record->flagByName->full_name;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            /*->editColumn('current_status', static function ($record) {
                if ($record->unflaged_by == 0) {
                       return 'Flagged';
                }
                else{
                    return 'Un-Flagged';
                }
            })*/
            ->editColumn('flagged_type', static function ($record) {
                if ($record->flagged_type == 'order') {
                    return 'On-Order';
                } else {
                    return 'On-Route';
                }
            })
            ->editColumn('joey_performance_status', static function ($record) {
                return backend_view('flag-orders.joey-performance-status-action', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('flag-orders.action', compact('record'));
            })
            ->make(true);

    }

    public function FlagOrderListPieChartData(Request $request)
    {

        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        // creating return data


//      $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $data->statistics ;
//      $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
        $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

        $hubIds = HubZones:://whereIn('zone_id',DB::raw('select zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id = '.$data.') '))
        whereIn('zone_id', function ($query) use ($statistics_id) {
            $query->select(
                DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
            );
        })
            ->pluck('hub_id')->toArray();

        $return_data = [
            'legend' => [],
            'data' => [],
        ];
        // getting data
        $FlagHistoryData = FlagHistory::whereNull('deleted_at')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->whereIn('hub_id', $hubIds)
            ->whereNull('unflaged_date')
            ->get();

        // looping on data
        foreach ($FlagHistoryData as $FlagHistory) {
            $category_nama = $FlagHistory->flag_cat_name;
            //pushing data into legend
            array_push($return_data['legend'], $category_nama);
            // now checking the value exist or not
            if (isset($return_data['data'][$category_nama])) {

                $return_data['data'][$category_nama]['value'] = $return_data['data'][$category_nama]['value'] + 1;
            } else {
                $return_data['data'][$category_nama] = ['name' => $category_nama, "value" => 1];
            }
        }

        // setting responce
        $return_data['legend'] = array_unique($return_data['legend'], SORT_REGULAR);
        $return_data['data'] = array_values($return_data['data']);

        return response()->json(['status' => true, 'body' => $return_data]);


    }

    public function FlagOrderDetails($id)
    {
        $JoeyPerformanceHistory = JoeyPerformanceHistory::where('flag_history_ref_id', $id)->where('order_type','ecommerce')->orderBy('id', 'DESC')->first();

        $AllFlagsOrderJoeys = $JoeyPerformanceHistory->where('joey_id', $JoeyPerformanceHistory->joey_id)->get();

        return backend_view('flag-orders.details', compact(
            'JoeyPerformanceHistory',
            'AllFlagsOrderJoeys'
        ));
    }

    /**
     * Get Approved Flag Orders
     */
    public function ApprovedFlagList()
    {

        $all_joeys_accept_selected = Joey::where('is_enabled', '=', 1)->where('deleted_at', null)->limit(10)->get();
        return backend_view('flag-orders.approved-list', compact('all_joeys_accept_selected'));

    }

    /**
     * Datatable Approved Order List
     */
    public function ApprovedFlagListData(Datatables $datatables, Request $request)
    {
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        //$start_date = $request->start_date." 00:00:00";
        //$end_date = $request->end_date." 23:59:59";
        $data = Auth::user();

//      $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $data->statistics ;
//      $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
        $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

        $hubIds = HubZones::whereIn('zone_id', function ($query) use ($statistics_id) {
            $query->select(
                DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
            );
        })
            ->pluck('hub_id')->toArray();

        $query = FlagHistory::whereIn('hub_id', $hubIds)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->where('is_approved', 1)
            ->whereNull('deleted_at');

        // filters
        if ($request->joeys != '') {
            $query->whereIn('joey_id', $request->joeys);
        }

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joeyName->FullName;
            })
            ->editColumn('flag_by', static function ($record) {
                return $record->flagByName->full_name;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->addColumn('action', static function ($record) {
                return backend_view('flag-orders.action', compact('record'));
            })
            ->make(true);

    }

    /**
     * Get Un-Approved Flag Orders
     */
    public function UnApprovedFlagList()
    {

        $all_joeys_accept_selected = Joey::where('is_enabled', '=', 1)->where('deleted_at', null)->limit(10)->get();
        return backend_view('flag-orders.un-approved-list', compact('all_joeys_accept_selected'));
    }

    /**
     * Datatable Un-Approved Order List
     */
    public function UnApprovedFlagListData(Datatables $datatables, Request $request)
    {
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        //$start_date = $request->start_date." 00:00:00";
        //$end_date = $request->end_date." 23:59:59";
        $data = Auth::user();

//      $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $data->statistics ;
//      $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
        $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

        $hubIds = HubZones::whereIn('zone_id', function ($query) use ($statistics_id) {
            $query->select(
                DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
            );
        })
            ->pluck('hub_id')->toArray();

        $query = FlagHistory::whereIn('hub_id', $hubIds)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->where('is_approved', 0)
            ->whereNull('unflaged_date');

        // filters
        if ($request->joeys != '') {
            $query->whereIn('joey_id', $request->joeys);
        }

        return $datatables->eloquent($query)
            ->addColumn('all', static function ($record) {
                return backend_view('flag-orders.check-box', compact('record'));
            })
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joeyName->FullName;
            })
            ->editColumn('flag_by', static function ($record) {
                return $record->flagByName->full_name;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->editColumn('joey_performance_status', static function ($record) {
                return backend_view('flag-orders.joey-performance-status-action', compact('record'));
            })
            ->addColumn('attachment_path', static function ($record) {
                if (isset($record->Sprint->sprintTasksFlag))
                {
                    $ids = $record->Sprint->sprintTasksFlag->id;
                    $sprint_confirmation = SprintConfirmation::where('task_id',$ids)->whereNull('deleted_at')->whereNotNull('attachment_path')->orderBy('id', 'desc')->get();
                    foreach ($sprint_confirmation as $sprint)
                    {
                        if (isset($sprint)) {
                            return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $sprint->attachment_path . '" />';
                        } else {
                            return '';
                        }
                    }
                }
                else{
                    return '';
                }

            })
            ->editColumn('joey_performance_status', static function ($record) {
                return backend_view('flag-orders.joey-performance-status-action', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('flag-orders.action', compact('record'));
            })
            ->make(true);

    }


    /**
     * Get Block Joeys By Flag
     */
    public function BlockJoeyFlagList(Request $request)
    {
        return backend_view('flag-orders.block-joey-flag-list');
    }

    /**
     *
     * block flag joeys list
     */
    public function BlockJoeyFlagListData(Datatables $datatables, Request $request)
    {
//        $query = JoyFlagLoginValidations::whereNull('deleted_at')->groupBy('joey_id');
        $query = JoyFlagLoginValidations::orderby('created_at', 'asc')
            ->whereNull('deleted_at')
            ->get();
        $group_joeys_ids = [];
        foreach ($query as $data) {
            $group_joeys_ids[$data->joey_id] = $data->id;
        }

        $query = JoyFlagLoginValidations::join('joey_performance_history', 'joey_flag_login_validations.joey_performance_history_id', '=', 'joey_performance_history.id')
            ->select(['joey_flag_login_validations.*', 'joey_performance_history.incident_value_applied as incident_value', 'joey_performance_history.id as joey_performance_id'])
            ->where('joey_performance_history.order_type','ecommerce')
            ->whereIn('joey_flag_login_validations.id', $group_joeys_ids);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('joey_id', static function ($record) {
                return $record->joeyName->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joeyName->FullName;
            })
            ->editColumn('joey_email', static function ($record) {
                return $record->joeyName->email;
            })
            ->editColumn('joey_phone', static function ($record) {
                return $record->joeyName->phone;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->editColumn('suspension_date', static function ($record) {
                // checking the date is exist
                if (!is_null($record->window_start) && !is_null($record->window_end)) {
                    return $record->window_start . ' to ' . $record->window_end;
                } else {
                    return 'Not defined';
                }

            })
            ->editColumn('incident_value', static function ($record) {
                return ucwords(str_replace("_", " ", $record->incident_value));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('flag-orders.block-list-action', compact('record'));
            })
            ->make(true);
    }

    /**
     *
     * Blocked Joeys
     */
    public function UnblockJoeyFlag($id)
    {

        $current_date = date("Y-m-d H:i:s");
        $remove = JoyFlagLoginValidations::where('joey_id', $id)
            ->whereNull('deleted_at')
            ->update(["deleted_at" => $current_date]);
        return response()->json(['status' => true, 'message' => 'Joey unblock successfully']);

    }
    /**
     *
     * Approve joey performance status
     */
    public function JoeyPerformanceStatus($id)
    {
        $extra_message = '';
        $extra_info = [];
        $current_date = date('Y-m-d');
        $do_logout = "not_logout";
        DB::beginTransaction();
        try {
            $flag_data = FlagHistory::where('id', $id)->whereNull('unflaged_date')->first();

            if (is_null($flag_data)) {
                return response()->json(['status' => false, 'message' => 'Someone Already Un-flag ']);
            }
            $flag_data->is_approved = 1;
            $flag_data->save();
            /*        FlagHistory::where('id', $id)
                        ->where('unflaged_by',0)
                        ->update([
                            "is_approved"=> 1
                        ]);*/
            $incident_count = JoeyPerformanceHistory::where('joey_id', $flag_data->joey_id)
                    ->where('flag_cat_id', $flag_data->flag_cat_id)
                    ->where('unflaged_by', '=', 0)
                    //->where('refresh_date', '>=', $current_date)
                    ->where(function ($query) use($current_date) {
                        $query->where('refresh_date', '>=', $current_date)
                            ->orWhereNull('refresh_date');
                    })
                    ->count() + 1;

            // flag cat incident value should applied
            $flag_incident_values = CustomerFlagCategoryValues::where('category_ref_id', $flag_data->flag_cat_id)->first()->toArray();

            // geting incident label
            $incident_label = '';
            $incident_label_finance = '';
            $rating_label = '';
            $incident_id = 1;

            $refresh_date = $current_date;

            // checking the incident is on conclusion or not
            if ($incident_count < 4) // for incident value
            {

                $incident_id = $flag_incident_values['incident_' . $incident_count . '_ref_id'];
                $finance_incident_value = $flag_incident_values['finance_incident_' . $incident_count];
                $finance_incident_operator = $flag_incident_values['finance_incident_' . $incident_count . '_operator'];
                $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';
                $rating_value = $flag_incident_values['rating_' . $incident_count];
                $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                $refresh_date = strval($flag_incident_values['refresh_rate_incident_' . $incident_count]);

            } elseif ($incident_count == 4) // for conclusion
            {
                $incident_id = $flag_incident_values['conclusion_ref_id'];
                $finance_incident_value = $flag_incident_values['finance_conclusion'];
                $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                $rating_value = $flag_incident_values['rating_' . $incident_count];
                $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
            } else // for termination
            {
                $incident_id = $flag_incident_values['conclusion_ref_id']; // this id for termination label
                $finance_incident_value = $flag_incident_values['finance_conclusion'];
                $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                $rating_value = $flag_incident_values['rating_4'];
                $rating_operator = $flag_incident_values['rating_4_operator'];
                $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
            }

            // calculating refresh rate
            $RefreshRateValueLabels = JoeyPerformanceHistory::RefreshRateValueLabels;

            if (array_key_exists($refresh_date, $RefreshRateValueLabels)) // checking the value exist in labels
            {
                $refresh_date = date('Y-m-d', strtotime($current_date . $RefreshRateValueLabels[$refresh_date]));
            }
            else
            {
                $refresh_date = null;
            }

            $Joey_performance_history_data = JoeyPerformanceHistory::create([
                'flag_history_ref_id' => $id,
                'route_id' => $flag_data->route_id,
                'joey_id' => $flag_data->joey_id,
                'tracking_id' => $flag_data->tracking_id,
                'sprint_id' => $flag_data->sprint_id,
                'hub_id' => $flag_data->hub_id,
                'flag_cat_id' => $flag_data->flag_cat_id,
                'flag_cat_name' => $flag_data->flag_cat_name,
                'flaged_by' => $flag_data->flaged_by,
                'portal_type' => 'dashboard',
                'flagged_type' => $flag_data->flagged_type,
                'incident_value_applied' => $incident_label,
                'finance_incident_value_applied' => $incident_label_finance,
                'rating_value' => $rating_label,
                'refresh_date' => $refresh_date,
                'order_type' => $flag_data->order_type
            ]);
            //checking logout condition push
            if ($incident_label != 'warning') {
                $do_logout = "logout";
            }

            //Getting joeys details to send notification
            $joey_data = Joey::where('id', '=', $flag_data->joey_id)
                ->first();


            if ($joey_data == null) {
                return response()->json(['status' => false, 'message' => 'This order has no joey for flag']);
            }

            //base64 convert
            //$email = base64_encode ("abdul.basit@joeyco.com");
            $email = base64_encode($joey_data->email);

            $joey_flag = ["route_id" => $flag_data->route_id, "sprint_no" => $flag_data->sprint_id, "flag_name" => $flag_data->flag_cat_name];

            //Sen mail to joey on assign flag
            $Joey_performance_history_data->sendFlagEmailToJoey($email, $joey_data, $joey_flag);

            if (empty($joey_flag['sprint_no'])) {
                $message = 'You are receiving this notification because Joeyco take action on you against this route number "' . $joey_flag['route_id'] . '" and marked flaged ' . $joey_flag['flag_name'];
            } else {
                $message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
            }
            //Checking condition phone num exist or not
            if ($joey_data->phone != null) {
                //set message to send

                $sid = "ACb414b973404343e8895b05d5be3cc056";
                $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                $twilio = new Client($sid, $token);
                try {

                    $message_twilio = $twilio->messages
                        //->create(+17087362094, // to
                        ->create($joey_data->phone,
                            [
                                "body" => $message,
                                "from" => "+16479316176"
                            ]
                        );

                } catch (\Exception $e) {
                    $extra_message = 'but we cannot send message due to invalid number';
                    $extra_info['twilio_code'] = $e->getCode();
                    $extra_info['twilio_message'] = $e->getMessage();
                }
            }

            if (isset($joey_data->id)) {
                //$deviceIds = UserDevice::where('user_id', 10080)->pluck('device_token');
                $deviceIds = UserDevice::where('user_id', $joey_data->id)->where('is_deleted_at','=',0)->pluck('device_token');
                $subject = 'Hi ' . $joey_data->first_name . ' ' . $joey_data->last_name;
                //$message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                Fcm::sendPush($subject, $message, 'flag', null, $deviceIds);
                $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'flag'],
                    'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'flag', 'do_logout' => $do_logout]];
                $createNotification = [
                    'user_id' => $joey_data->id,
                    'user_type' => 'Joey',
                    'notification' => $subject,
                    'notification_type' => 'flag',
                    'notification_data' => json_encode(["body" => $message]),
                    'payload' => json_encode($payload),
                    'is_silent' => 0,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                UserNotification::create($createNotification);
            }

            // set login validation
            $login_validation = new JoyFlagLoginValidationsHandler();
            $login_validation->setValues($flag_data->joey_id, $incident_id, $Joey_performance_history_data->id);
            $login_validation->applyAction();
            // sending curl request
            // initing the curlRequst
            $curl = new  CurlRequestSend();
            $curl->setHeader('Cross-origin-token','Cross-origin-token: NWZhZmRjZmRkMDI5MjkuMzEzNDEzNTA=')
                ->setHost('https://finance.joeyco.com')
                ->setMethod('post')
                ->setUri('api/v1/payout-update-hendler');
            // getting task id by tracking id
            $merchantids = null ;
            if(!empty($flag_data->tracking_id) && !is_null($flag_data->tracking_id))
            {
                $merchantids = MerchantIds::where('tracking_id',$flag_data->tracking_id)
                    ->whereNull('deleted_at')
                    ->orderBy('id', 'DESC')
                    ->first();
            }
            // now checking the order is in route or not
            if($flag_data->route_id > 0)
            {
                $curl->setData(
                    [
                        'route_id' => $flag_data->route_id,
                        'task_id' => (isset($merchantids->task_id))? $merchantids->task_id: null,
                        'joey_id' => $flag_data->joey_id,
                        'update_type' => '2',
                        'update_for' => 'route_orders',
                        'meta_data' => stripslashes('{"finance_incident_value_applied":'.$incident_label_finance.',"Joey_performance_history_id":'.$Joey_performance_history_data->id.'}')
                    ]
                );
            }
            elseif(1==2) // this block is used with out route orders
            {
            }
            $finance_portal_response = $curl->send()
                ->rawResponce();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Joey flag approved successfully ' . $extra_message . ' !', 'extra_info' => $extra_info]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }

    }

    public function multipleApprovedFlag(Request $request)
    {
        $extra_message = '';
        $extra_info = [];
        $current_date = date('Y-m-d');
        $do_logout = "not_logout";
        DB::beginTransaction();
        try {
            $request_data = $request->data;

            foreach($request_data as $key => $single_request) {
                $flag_data = FlagHistory::where('id', $single_request['flag_id'])->whereNull('unflaged_date')->first();

                if (is_null($flag_data)) {
                    return response()->json(['status' => false, 'message' => 'Someone Already Un-flag ']);
                }
                $flag_data->is_approved = 1;
                $flag_data->save();

                $incident_count = JoeyPerformanceHistory::where('joey_id', $flag_data->joey_id)
                        ->where('flag_cat_id', $flag_data->flag_cat_id)
                        ->where('unflaged_by', '=', 0)
                        ->where(function ($query) use($current_date) {
                            $query->where('refresh_date', '>=', $current_date)
                                ->orWhereNull('refresh_date');
                        })
                        ->count() + 1;

                // flag cat incident value should applied
                $flag_incident_values = CustomerFlagCategoryValues::where('category_ref_id', $flag_data->flag_cat_id)->first()->toArray();


                // geting incident label
                $incident_label = '';
                $incident_label_finance = '';
                $rating_label = '';
                $incident_id = 1;

                $refresh_date = $current_date;

                // checking the incident is on conclusion or not
                if ($incident_count < 4) // for incident value
                {

                    $incident_id = $flag_incident_values['incident_' . $incident_count . '_ref_id'];

                    $finance_incident_value = $flag_incident_values['finance_incident_' . $incident_count];
                    $finance_incident_operator = $flag_incident_values['finance_incident_' . $incident_count . '_operator'];
                    $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                    $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                    $rating_value = $flag_incident_values['rating_' . $incident_count];
                    $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                    $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                    $refresh_date = strval($flag_incident_values['refresh_rate_incident_' . $incident_count]);

                } elseif ($incident_count == 4) // for conclusion
                {
                    $incident_id = $flag_incident_values['conclusion_ref_id'];
                    $finance_incident_value = $flag_incident_values['finance_conclusion'];
                    $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                    $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                    $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                    $rating_value = $flag_incident_values['rating_' . $incident_count];
                    $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                    $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                    $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
                } else // for termination
                {
                    $incident_id = $flag_incident_values['conclusion_ref_id']; // this id for termination label
                    $finance_incident_value = $flag_incident_values['finance_conclusion'];
                    $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                    $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                    $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                    $rating_value = $flag_incident_values['rating_4'];
                    $rating_operator = $flag_incident_values['rating_4_operator'];
                    $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                    $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
                }

                // calculating refresh rate
                $RefreshRateValueLabels = JoeyPerformanceHistory::RefreshRateValueLabels;

                if (array_key_exists($refresh_date, $RefreshRateValueLabels)) // checking the value exist in labels
                {
                    $refresh_date = date('Y-m-d', strtotime($current_date . $RefreshRateValueLabels[$refresh_date]));
                }
                else
                {
                    $refresh_date = null;
                }

                $Joey_performance_history_data = JoeyPerformanceHistory::create([
                    'flag_history_ref_id' => $single_request['flag_id'],
                    'route_id' => $flag_data->route_id,
                    'joey_id' => $flag_data->joey_id,
                    'tracking_id' => $flag_data->tracking_id,
                    'sprint_id' => $flag_data->sprint_id,
                    'hub_id' => $flag_data->hub_id,
                    'flag_cat_id' => $flag_data->flag_cat_id,
                    'flag_cat_name' => $flag_data->flag_cat_name,
                    'flaged_by' => $flag_data->flaged_by,
                    'portal_type' => 'dashboard',
                    'flagged_type' => $flag_data->flagged_type,
                    'incident_value_applied' => $incident_label,
                    'finance_incident_value_applied' => $incident_label_finance,
                    'rating_value' => $rating_label,
                    'refresh_date' => $refresh_date,
                    'order_type' => $flag_data->order_type
                ]);
                //checking logout condition push
                if ($incident_label != 'warning') {
                    $do_logout = "logout";
                }
                //Getting joeys details to send notification
                $joey_data = Joey::where('id', '=', $flag_data->joey_id)
                    ->first();
                if ($joey_data == null) {
                    return response()->json(['status' => false, 'message' => 'This order has no joey for flag']);
                }
                //base64 convert
                //$email = base64_encode ("abdul.basit@joeyco.com");
                $email = base64_encode($joey_data->email);

                $joey_flag = ["route_id" => $flag_data->route_id, "sprint_no" => $flag_data->sprint_id, "flag_name" => $flag_data->flag_cat_name];
                //Sen mail to joey on assign flag
                $Joey_performance_history_data->sendFlagEmailToJoey($email, $joey_data, $joey_flag);

                if (empty($joey_flag['sprint_no'])) {
                    $message = 'You are receiving this notification because Joeyco take action on you against this route number "' . $joey_flag['route_id'] . '" and marked flaged ' . $joey_flag['flag_name'];
                } else {
                    $message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                }
                //Checking condition phone num exist or not
                if ($joey_data->phone != null) {
                    //set message to send

                    $sid = "ACb414b973404343e8895b05d5be3cc056";
                    $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                    $twilio = new Client($sid, $token);
                    try {

                        $message_twilio = $twilio->messages
                            //->create(+17087362094, // to
                            ->create($joey_data->phone,
                                [
                                    "body" => $message,
                                    "from" => "+16479316176"
                                ]
                            );

                    } catch (\Exception $e) {
                        $extra_message = 'but we cannot send message due to invalid number';
                        $extra_info['twilio_code'] = $e->getCode();
                        $extra_info['twilio_message'] = $e->getMessage();
                    }
                }

                if (isset($joey_data->id)) {
                    //$deviceIds = UserDevice::where('user_id', 10080)->pluck('device_token');
                    $deviceIds = UserDevice::where('user_id', $joey_data->id)->where('is_deleted_at','=',0)->pluck('device_token');
                    $subject = 'Hi ' . $joey_data->first_name . ' ' . $joey_data->last_name;
                    //$message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                    Fcm::sendPush($subject, $message, 'flag', null, $deviceIds);
                    $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'flag'],
                        'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'flag', 'do_logout' => $do_logout]];
                    $createNotification = [
                        'user_id' => $joey_data->id,
                        'user_type' => 'Joey',
                        'notification' => $subject,
                        'notification_type' => 'flag',
                        'notification_data' => json_encode(["body" => $message]),
                        'payload' => json_encode($payload),
                        'is_silent' => 0,
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    UserNotification::create($createNotification);
                }


                // set login validation
                $login_validation = new JoyFlagLoginValidationsHandler();
                $login_validation->setValues($flag_data->joey_id, $incident_id, $Joey_performance_history_data->id);
                $login_validation->applyAction();

                // sending curl request
                // initing the curlRequst
                $curl = new  CurlRequestSend();
                $curl->setHeader('Cross-origin-token','Cross-origin-token: NWZhZmRjZmRkMDI5MjkuMzEzNDEzNTA=')
                    ->setHost('https://finance.joeyco.com')
                    ->setMethod('post')
                    ->setUri('api/v1/payout-update-hendler');
                // getting task id by tracking id
                $merchantids = null ;
                if(!empty($flag_data->tracking_id) && !is_null($flag_data->tracking_id))
                {
                    $merchantids = MerchantIds::where('tracking_id',$flag_data->tracking_id)
                        ->whereNull('deleted_at')
                        ->orderBy('id', 'DESC')
                        ->first();
                }
                // now checking the order is in route or not
                if($flag_data->route_id > 0)
                {
                    $curl->setData(
                        [
                            'route_id' => $flag_data->route_id,
                            'task_id' => (isset($merchantids->task_id))? $merchantids->task_id: null,
                            'joey_id' => $flag_data->joey_id,
                            'update_type' => '2',
                            'update_for' => 'route_orders',
                            'meta_data' => stripslashes('{"finance_incident_value_applied":'.$incident_label_finance.',"Joey_performance_history_id":'.$Joey_performance_history_data->id.'}')
                        ]
                    );
                }
                elseif(1==2) // this block is used with out route orders
                {
                }
                $finance_portal_response = $curl->send()
                    ->rawResponce();
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Joey flag approved successfully ' . $extra_message . ' !', 'extra_info' => $extra_info]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }
    }

    //Create Flag
    public function createFlag($flag_cat_id, Request $request)
    {

        DB::beginTransaction();
        try {
            //check if route already flag or not
            $route_flag = FlagHistory::where('route_id', $request->route_id)->where('flagged_type', 'route')->whereNull('unflaged_date')->first();

            if (isset($route_flag)) {
                return response()->json(['status' => false, 'message' => 'This route is already flagged']);
            }

            // getting category data
            $flag_category = CustomerFlagCategories::where('id', $flag_cat_id)->first();

            // checking joey id is exist
            $joey_id = $request->joey_id;
            if ($joey_id <= 0) {
                $joey_id = JoeyRoutes::where('id', $request->route_id)
                    ->latest()
                    ->first();
                // checking the route is exist
                if (is_null($joey_id)) {
                    return response()->json(['status' => false, 'message' => 'Route is not assigned ']);
                }
                // setting joey_id
                $joey_id = $joey_id->joey_id;
            }
            //check joey exits on this route or not
            if (is_null($joey_id)) {
                return response()->json(['status' => false, 'message' => 'Joey has not assigned in this route , you are not able to mark a flag.']);
            }
            //Mark Flag Against Joey
            $Joey_flag_history_data = FlagHistory::create([
                'joey_id' => $joey_id,
                'route_id' => $request->route_id,
                'tracking_id' => $request->tracking_id,
                'sprint_id' => $request->sprint,
                'hub_id' => $request->hub_id,
                'flag_cat_id' => $flag_category->id,
                'flag_cat_name' => $flag_category->category_name,
                'flaged_by' => Auth::user()->id,
                'flagged_type' => $request->flag_type,
                'portal_type' => 'dashboard',
                'order_type' => $request->order_type,
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'This ' . $request->flag_type . ' flagged successfully']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }
    }

    //un-flag order
    public function unFlag($unFlag_id)
    {
        //dd($unFlag_id);
        //getting data for un-flag order
        //$unflag = FlagHistory::find($unFlag_id);
        $unflag = FlagHistory::where('id', $unFlag_id)->first();

        if (is_null($unflag)) {
            return redirect()->back()
                ->with('error', 'The data dose not  exist');

        } elseif ($unflag->is_approved == 1) {
            return redirect()->back()
                ->with('error', 'This flag already approved');
        }

        //Getting joeys details to send notification
        $joey_data = Joey::where('id', '=', $unflag->joey_id)
            ->first();

        //checking condition data exist or not
        if (is_null($unflag)) {
            return redirect()->back()
                ->with('alert-danger', 'The id does`nt exist');
        }

        //Update Sprint For Return Order
        $unflag->unflaged_by = Auth::user()->id;
        $unflag->unflaged_date = date('Y-m-d H:i:s');
        $unflag->save();

        //base64 convert email
        //$email = base64_encode ("abdul.basit@joeyco.com");
        $email = base64_encode($joey_data->email);

        //getting flag details
        $joey_flag = ["route_id" => $unflag->route_id, "sprint_no" => $unflag->sprint_id, "flag_name" => $unflag->flag_cat_name];


        //Mail send to joeys on un-flag
        //$unflag->sendUnFlagEmailToJoey($email,$joey_data,$joey_flag);

//        //Checking condition phone num exist or not
//        if ($joey_data->phone != null)
//        {
//            if (empty($joey_flag['sprint_no']))
//            {
//                $message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this route number "' . $joey_flag['route_id'] . '".';
//            }
//            else
//            {
//                $message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
//            }
//            //$message = 'You are receiving this sms because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
//            $sid = "ACb414b973404343e8895b05d5be3cc056";
//            $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
//            $twilio = new Client($sid, $token);
//            try {
//
//                $message_twilio = $twilio->messages
//                    //->create(+17087362094, // to
//                    ->create($joey_data->phone, // to
//                        [
//                            "body" => $message,
//                            "from" => "+16479316176"
//                            //16477990253
//                        ]
//                    );
//
//            } catch (\Exception $e) {
//                echo $e->getCode() . ' : ' . $e->getMessage() . "<br>";
//            }
//        }

        if (isset($joey_data->id)) {
            if (empty($joey_flag['sprint_no'])) {
                $message = 'You are receiving this notification because Joeyco remove flag "' . $joey_flag['flag_name'] . '" on you against this route number "' . $joey_flag['route_id'] . '".';
            } else {
                $message = 'You are receiving this notification because Joeyco remove flag "' . $joey_flag['flag_name'] . '" on you against this order number "' . $joey_flag['sprint_no'] . '".';
            }
            //$deviceIds = UserDevice::where('user_id', 10080)->pluck('device_token');
            $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
            $subject = 'Hi ' . $joey_data->first_name . ' ' . $joey_data->last_name;
            //$message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
            //Fcm::sendPush($subject, $message, 'itinerary', null, $deviceIds);
            $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'itinerary'],
                'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'itinerary']];
            $createNotification = [
                'user_id' => $joey_data->id,
                'user_type' => 'Joey',
                'notification' => $subject,
                'notification_type' => 'itinerary',
                'notification_data' => json_encode(["body" => $message]),
                'payload' => json_encode($payload),
                'is_silent' => 0,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            UserNotification::create($createNotification);
        }

        //$deviceIds = UserDevice::where('user_id',10080)->pluck('device_token');
        //Push Notification
        //Fcm::sendPush('Hi '.$joey_data->nickname, 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".',null,null, $deviceIds);

        return redirect()->back()
            ->with('alert-success', 'This ' . $unflag->flagged_type . ' is un-flag successfully');

    }


    /**
     * Grocery Flag Order Functions
     **/
    //Create Grocery Flag
    public function groceryCreateFlag($flag_cat_id, Request $request)
    {
        DB::beginTransaction();
        try {
            //check if route already flag or not
            $route_flag = FlagHistory::where('route_id', $request->route_id)->where('flagged_type', 'route')->whereNull('unflaged_date')->first();

            if (isset($route_flag)) {
                return response()->json(['status' => false, 'message' => 'This route is already flagged']);
            }

            // getting category data
            $flag_category = CustomerFlagCategories::where('id', $flag_cat_id)->first();

            // checking joey id is exist
            if (!empty($request->joey_id))
            {
                $joey_id = $request->joey_id;
            }
            elseif (!empty($request->merchant_joey_id))
            {
                $joey_id = $request->merchant_joey_id;
            }


            //if (!empty($request->route_id)) {
            //   $route_id = JoeyRoutes::where('id', $request->route_id)
            //       ->latest()
            //      ->first();
            // checking the route is exist
            //    if (is_null($route_id)) {
            //        return response()->json(['status' => false, 'message' => 'Route is not assigned ']);
            //    }
            //}

            //check joey exits on this route or not
            if (empty($joey_id)) {
                return response()->json(['status' => false, 'message' => 'Joey has not assigned in this Order , you are not able to mark a flag.']);
            }
            //Mark Flag Against Joey
            $Joey_flag_history_data = FlagHistory::create([
                'joey_id' => $joey_id,
                'route_id' => null ,
                'tracking_id' => null,
                'merchant_order_num' => !empty($request->merchant_order_no) ? $request->merchant_order_no : null,
                'sprint_id' => $request->sprint,
                'hub_id' => null,
                'flag_cat_id' => $flag_category->id,
                'flag_cat_name' => $flag_category->category_name,
                'flaged_by' => Auth::user()->id,
                'flagged_type' => $request->flag_type,
                'portal_type' => 'dashboard',
                'order_type' => $request->order_type,
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'This ' . $request->flag_type . ' flagged successfully']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }
    }

    //un-flag Grocery order
    public function groceryUnFlag($unFlag_id)
    {
        //dd($unFlag_id);
        //getting data for un-flag order
        //$unflag = FlagHistory::find($unFlag_id);
        $unflag = FlagHistory::where('id', $unFlag_id)->first();

        if (is_null($unflag)) {
            return redirect()->back()
                ->with('error', 'The data dose not  exist');

        } elseif ($unflag->is_approved == 1) {
            return redirect()->back()
                ->with('error', 'This flag already approved');
        }

        //Getting joeys details to send notification
        $joey_data = Joey::where('id', '=', $unflag->joey_id)
            ->first();

        //checking condition data exist or not
        if (is_null($unflag)) {
            return redirect()->back()
                ->with('alert-danger', 'The id does`nt exist');
        }

        //Update Sprint For Return Order
        $unflag->unflaged_by = Auth::user()->id;
        $unflag->unflaged_date = date('Y-m-d H:i:s');
        $unflag->save();

        //base64 convert email
        //$email = base64_encode ("abdul.basit@joeyco.com");
        $email = base64_encode($joey_data->email);

        //getting flag details
        $joey_flag = ["route_id" => $unflag->route_id, "sprint_no" => $unflag->sprint_id, "flag_name" => $unflag->flag_cat_name];


        //Mail send to joeys on un-flag
        //$unflag->sendUnFlagEmailToJoey($email,$joey_data,$joey_flag);

//        //Checking condition phone num exist or not
//        if ($joey_data->phone != null)
//        {
//            if (!empty($joey_flag['route_id']))
//            {
//                $message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this route number "' . $joey_flag['route_id'] . '".';
//            }
//            else
//            {
//                $message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
//            }
//            //$message = 'You are receiving this sms because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
//            $sid = "ACb414b973404343e8895b05d5be3cc056";
//            $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
//            $twilio = new Client($sid, $token);
//            try {
//
//                $message_twilio = $twilio->messages
//                    //->create(+17087362094, // to
//                    ->create($joey_data->phone, // to
//                        [
//                            "body" => $message,
//                            "from" => "+16479316176"
//                            //16477990253
//                        ]
//                    );
//
//            } catch (\Exception $e) {
//                echo $e->getCode() . ' : ' . $e->getMessage() . "<br>";
//            }
//        }

        if (isset($joey_data->id)) {

            if (!empty($joey_flag['route_id'])) {
                $message = 'You are receiving this notification because Joeyco remove flag "' . $joey_flag['flag_name'] . '" on you against this route number "' . $joey_flag['route_id'] . '".';
            } else {
                $message = 'You are receiving this notification because Joeyco remove flag "' . $joey_flag['flag_name'] . '" on you against this order number "' . $joey_flag['sprint_no'] . '".';
            }

            //$deviceIds = UserDevice::where('user_id', 10080)->pluck('device_token');
            $deviceIds = UserDevice::where('user_id', $joey_data->id)->pluck('device_token');
            $subject = 'Hi ' . $joey_data->first_name . ' ' . $joey_data->last_name;
            //$message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
            //Fcm::sendPush($subject, $message, 'itinerary', null, $deviceIds);
            $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'itinerary'],
                'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'itinerary']];
            $createNotification = [
                'user_id' => $joey_data->id,
                'user_type' => 'Joey',
                'notification' => $subject,
                'notification_type' => 'itinerary',
                'notification_data' => json_encode(["body" => $message]),
                'payload' => json_encode($payload),
                'is_silent' => 0,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            UserNotification::create($createNotification);
        }

        //$deviceIds = UserDevice::where('user_id',10080)->pluck('device_token');
        //Push Notification
        //Fcm::sendPush('Hi '.$joey_data->nickname, 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".',null,null, $deviceIds);

        return redirect()->back()
            ->with('alert-success', 'This ' . $unflag->flagged_type . ' is un-flag successfully');

    }

    /**
     * Get Flag Orders
     */
    public function groceryFlagOrderList(Request $request)
    {
        

        $data = $request->all();
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        $user = Auth::user();
       
        //Getting all joeys flag marked
        $all_flag_joey = FlagHistory::where('unflaged_by', 0)
            ->whereNull('hub_id')
            ->groupBy('joey_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->get();
        //Getting all flag marked orders
        $all_flag_mark = FlagHistory::where('unflaged_by', 0)
            ->whereNull('hub_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting all flag marked orders
        $all_un_flag_mark = FlagHistory::where('unflaged_by', '>', 0)
            ->whereNull('hub_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting all approved flagged orders
        $all_approved_flag = FlagHistory::where('is_approved', 1)
            ->whereNull('hub_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
        //Getting all un-approved flag orders
        $all_un_approved_flag = FlagHistory::where('unflaged_by', 0)
            ->whereNull('hub_id')
            ->where('is_approved', 0)
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->count();
          
        //Getting All Blocked Joeys By Flag
        $blocked_joeys_by_flag = JoyFlagLoginValidations::where('is_blocked', 1)
            ->whereNull('deleted_at')
            ->groupBy('joey_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->get();
        


        $selectjoey = isset($data['joey']) ? $request->get('joey') : '';
        //dd($all_un_flag_mark);
        //dd($selectjoey);
        $all_joeys_accept_selected = Joey::where('is_enabled', '=', 1)->where('deleted_at', null)->limit(10)->get();

        return backend_view('grocery-flag-orders.grocery-index',
            compact(
                'all_joeys_accept_selected',
                'selectjoey',
                'all_flag_joey',
                'all_flag_mark',
                'all_approved_flag',
                'all_un_approved_flag',
                'all_un_flag_mark',
                'blocked_joeys_by_flag'
            )
        );
    }

    /**
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function groceryFlagOrderListData(Datatables $datatables, Request $request)
    {
       

        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');

        $query = FlagHistory::whereNull('deleted_at')
            ->whereNull('hub_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted]);

        // filters
        if ($request->joeys != '') {
            $query->where('joey_id', $request->joeys);
        }

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return isset($record->joeyName->FullName) ? $record->joeyName->FullName : '';
            })
            ->editColumn('flag_by', static function ($record) {
                return $record->flagByName->full_name;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->editColumn('flagged_type', static function ($record) {
                if ($record->flagged_type == 'order') {
                    return 'On-Order';
                } else {
                    return 'On-Route';
                }
            })
            ->editColumn('joey_performance_status', static function ($record) {
                return backend_view('grocery-flag-orders.joey-performance-status-action', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('grocery-flag-orders.action', compact('record'));
            })
            ->make(true);

    }

    public function groceryFlagOrderListPieChartData(Request $request)
    {

        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');
        // creating return data

        $return_data = [
            'legend' => [],
            'data' => [],
        ];
        // getting data
        $FlagHistoryData = FlagHistory::whereNull('deleted_at')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->whereNull('hub_id')
            ->where('order_type','grocery')
            ->whereNull('unflaged_date')
            ->get();

        // looping on data
        foreach ($FlagHistoryData as $FlagHistory) {
            $category_nama = $FlagHistory->flag_cat_name;
            //pushing data into legend
            array_push($return_data['legend'], $category_nama);
            // now checking the value exist or not
            if (isset($return_data['data'][$category_nama])) {

                $return_data['data'][$category_nama]['value'] = $return_data['data'][$category_nama]['value'] + 1;
            } else {
                $return_data['data'][$category_nama] = ['name' => $category_nama, "value" => 1];
            }
        }

        // setting responce
        $return_data['legend'] = array_unique($return_data['legend'], SORT_REGULAR);
        $return_data['data'] = array_values($return_data['data']);

        return response()->json(['status' => true, 'body' => $return_data]);


    }

    public function groceryFlagOrderDetails($id)
    {
        $JoeyPerformanceHistory = JoeyPerformanceHistory::where('flag_history_ref_id', $id)->where('order_type','grocery')->orderBy('id', 'DESC')->first();

        $AllFlagsOrderJoeys = $JoeyPerformanceHistory->where('joey_id', $JoeyPerformanceHistory->joey_id)->where('order_type','grocery')->get();

        return backend_view('grocery-flag-orders.details', compact(
            'JoeyPerformanceHistory',
            'AllFlagsOrderJoeys'
        ));
    }

    /**
     * Grocery Approve joey performance status
     **/
    public function groceryJoeyPerformanceStatus($id)
    {
        $extra_message = '';
        $extra_info = [];
        $current_date = date('Y-m-d');
        $do_logout = "not_logout";
        DB::beginTransaction();
        try {
            $flag_data = FlagHistory::where('id', $id)->whereNull('unflaged_date')->first();

            if (is_null($flag_data)) {
                return response()->json(['status' => false, 'message' => 'Someone Already Un-flag ']);
            }
            $flag_data->is_approved = 1;
            $flag_data->save();
            /*        FlagHistory::where('id', $id)
                        ->where('unflaged_by',0)
                        ->update([
                            "is_approved"=> 1
                        ]);*/

            $incident_count = JoeyPerformanceHistory::where('joey_id', $flag_data->joey_id)
                    ->where('flag_cat_id', $flag_data->flag_cat_id)
                    ->where('unflaged_by', '=', 0)
                    //->where('refresh_date', '>=', $current_date)
                    ->where(function ($query) use($current_date) {
                        $query->where('refresh_date', '>=', $current_date)
                            ->orWhereNull('refresh_date');
                    })
                    ->count() + 1;

            // flag cat incident value should applied
            $flag_incident_values = CustomerFlagCategoryValues::where('category_ref_id', $flag_data->flag_cat_id)->first()->toArray();

            //Get Grand Parent Name
            $childParentName = CustomerFlagCategories::where('id',$flag_data->flag_cat_id)->first();
            $ParentName = CustomerFlagCategories::where('id',$childParentName->parent_id)->first();
            //$grandParentName = CustomerFlagCategories::where('id',$ParentName->parent_id)->first();

            // geting incident label
            $incident_label = '';
            $incident_label_finance = '';
            $rating_label = '';
            $incident_id = 1;

            $refresh_date = $current_date;

            // checking the incident is on conclusion or not
            if ($incident_count < 4) // for incident value
            {

                $incident_id = $flag_incident_values['incident_' . $incident_count . '_ref_id'];

                $finance_incident_value = $flag_incident_values['finance_incident_' . $incident_count];
                $finance_incident_operator = $flag_incident_values['finance_incident_' . $incident_count . '_operator'];
                $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                $rating_value = $flag_incident_values['rating_' . $incident_count];
                $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                $refresh_date = strval($flag_incident_values['refresh_rate_incident_' . $incident_count]);

            } elseif ($incident_count == 4) // for conclusion
            {
                $incident_id = $flag_incident_values['conclusion_ref_id'];
                $finance_incident_value = $flag_incident_values['finance_conclusion'];
                $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                $rating_value = $flag_incident_values['rating_' . $incident_count];
                $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
            } else // for termination
            {
                $incident_id = $flag_incident_values['conclusion_ref_id']; // this id for termination label
                $finance_incident_value = $flag_incident_values['finance_conclusion'];
                $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                $rating_value = $flag_incident_values['rating_4'];
                $rating_operator = $flag_incident_values['rating_4_operator'];
                $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
            }

            // calculating refresh rate
            $RefreshRateValueLabels = JoeyPerformanceHistory::RefreshRateValueLabels;

            if (array_key_exists($refresh_date, $RefreshRateValueLabels)) // checking the value exist in labels
            {
                $refresh_date = date('Y-m-d', strtotime($current_date . $RefreshRateValueLabels[$refresh_date]));
            }
            else
            {
                $refresh_date = null;
            }

            $Joey_performance_history_data = JoeyPerformanceHistory::create([
                'flag_history_ref_id' => $id,
                'route_id' => $flag_data->route_id,
                'joey_id' => $flag_data->joey_id,
                'tracking_id' => $flag_data->tracking_id,
                'merchant_order_num' => $flag_data->merchant_order_num,
                'sprint_id' => $flag_data->sprint_id,
                'hub_id' => $flag_data->hub_id,
                'flag_cat_id' => $flag_data->flag_cat_id,
                'flag_cat_name' => $flag_data->flag_cat_name,
                'flaged_by' => $flag_data->flaged_by,
                'portal_type' => 'dashboard',
                'flagged_type' => $flag_data->flagged_type,
                'incident_value_applied' => $incident_label,
                'finance_incident_value_applied' => $incident_label_finance,
                'rating_value' => $rating_label,
                'refresh_date' => $refresh_date,
                'order_type' => $flag_data->order_type
            ]);

            $lastJoeyTransaction=JoeyTransactions::where('joey_id',$flag_data->joey_id)->orderBy('transaction_id','desc')->first();

            $flagAmount = '';
            $balanceValue = '';
            if ($Joey_performance_history_data->JosnValuesDecode('finance')['operator'] == '-')
            {
                $paymentType = '-Deduction';
                $joeyPaymentType = 'penalty';
                $flagAmount = -$Joey_performance_history_data->JosnValuesDecode('finance')['value'];
                $balanceValue = (isset($lastJoeyTransaction->balance)?$lastJoeyTransaction->balance:0)-$Joey_performance_history_data->JosnValuesDecode('finance')['value'];
            }
            else
            {
                $paymentType = '-Bonus';
                $joeyPaymentType = 'bonus';
                $flagAmount = +$Joey_performance_history_data->JosnValuesDecode('finance')['value'];
                $balanceValue = (isset($lastJoeyTransaction->balance)?$lastJoeyTransaction->balance:0)+$Joey_performance_history_data->JosnValuesDecode('finance')['value'];
            }
            //Finance Transaction
            $transaction=FinancialTransactions::create([
                'reference'=>'CR-'.$flag_data->sprint_id.$paymentType??null,
                'description'=>$ParentName->category_name,
                'amount'=> $flagAmount,
                'merchant_order_num'=>($flag_data->merchant_order_num!=null)?$flag_data->merchant_order_num:null
            ]);

            //Joey Transaction
            $joeyTransactionsData=[
                'transaction_id'=>$transaction->id,
                'joey_id'=>$flag_data->joey_id,
                'type'=>$joeyPaymentType,
                'payment_method'=>null,
                'distance'=>null,
                'duration'=>null,
                'date_identifier'=>null,
                'shift_id'=>null,
                'balance'=>$balanceValue,
            ];
            JoeyTransactions::insert($joeyTransactionsData);
            $balance=$joeyTransactionsData['balance'];
            Joey::where('id',$flag_data->joey_id)->update(['balance'=> $balance]);



            //checking logout condition push
            if ($incident_label != 'warning') {
                $do_logout = "logout";
            }
            //Getting joeys details to send notification
            $joey_data = Joey::where('id', '=', $flag_data->joey_id)
                ->first();
            if ($joey_data == null) {
                return response()->json(['status' => false, 'message' => 'This order has no joey for flag']);
            }
            //base64 convert
            //$email = base64_encode ("abdul.basit@joeyco.com");
            $email = base64_encode($joey_data->email);

            $joey_flag = ["route_id" => $flag_data->route_id, "sprint_no" => $flag_data->sprint_id, "flag_name" => $flag_data->flag_cat_name];
            //Send mail to joey on assign flag
            $Joey_performance_history_data->sendFlagEmailToJoey($email, $joey_data, $joey_flag);

            if (!empty($joey_flag['route_id'])) {
                $message = 'You are receiving this notification because Joeyco take action on you against this route number "' . $joey_flag['route_id'] . '" and marked flaged ' . $joey_flag['flag_name'];
            } else {
                $message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
            }
            //Checking condition phone num exist or not
            if ($joey_data->phone != null) {
                //set message to send

                $sid = "ACb414b973404343e8895b05d5be3cc056";
                $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                $twilio = new Client($sid, $token);
                try {

                    $message_twilio = $twilio->messages
                        //->create(+17087362094, // to
                        ->create($joey_data->phone,
                            [
                                "body" => $message,
                                "from" => "+16479316176"
                            ]
                        );

                }
                catch (\Exception $e) {
                    $extra_message = 'but we cannot send message due to invalid number';
                    $extra_info['twilio_code'] = $e->getCode();
                    $extra_info['twilio_message'] = $e->getMessage();
                }
            }

            if (isset($joey_data->id)) {
                //$deviceIds = UserDevice::where('user_id', 10080)->pluck('device_token');
                $deviceIds = UserDevice::where('user_id', $joey_data->id)->where('is_deleted_at','=',0)->pluck('device_token');
                $subject = 'Hi ' . $joey_data->first_name . ' ' . $joey_data->last_name;
                //$message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                Fcm::sendPush($subject, $message, 'flag', null, $deviceIds);
                $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'flag'],
                    'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'flag', 'do_logout' => $do_logout]];
                $createNotification = [
                    'user_id' => $joey_data->id,
                    'user_type' => 'Joey',
                    'notification' => $subject,
                    'notification_type' => 'flag',
                    'notification_data' => json_encode(["body" => $message]),
                    'payload' => json_encode($payload),
                    'is_silent' => 0,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                UserNotification::create($createNotification);
            }


            // set login validation
            $login_validation = new JoyFlagLoginValidationsHandler();
            $login_validation->setValues($flag_data->joey_id, $incident_id, $Joey_performance_history_data->id);
            $login_validation->applyAction();

            // sending curl request
            // initing the curlRequst
            /* $curl = new  CurlRequestSend();
             $curl->setHeader('Cross-origin-token','Cross-origin-token: NWZhZmRjZmRkMDI5MjkuMzEzNDEzNTA=')
                 ->setHost('https://finance.joeyco.com')
                 ->setMethod('post')
                 ->setUri('api/v1/payout-update-hendler');
             // getting task id by tracking id
             $merchantids = null ;
             if(!empty($flag_data->merchant_order_num) && !is_null($flag_data->merchant_order_num))
             {
                 $merchantids = MerchantIds::where('merchant_order_num',$flag_data->merchant_order_num)
                     ->whereNull('deleted_at')
                     ->orderBy('id', 'DESC')
                     ->first();
             }
             // now checking the order is in route or not
             if(empty($flag_data->route_id)) // this block is used with out route orders
             {
                 $curl->setData(
                     [
                         //'route_id' => $flag_data->route_id,
                         'task_id' => (isset($merchantids->task_id))? $merchantids->task_id: null,
                         'joey_id' => $flag_data->joey_id,
                         'update_type' => '2',
                         //'update_for' => 'route_orders',
                         'meta_data' => stripslashes('{"finance_incident_value_applied":'.$incident_label_finance.',"Joey_performance_history_id":'.$Joey_performance_history_data->id.'}')
                     ]
                 );
             }
             $finance_portal_response = $curl->send()
                 ->rawResponce();*/

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Joey flag approved successfully ' . $extra_message . ' !', 'extra_info' => $extra_info]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }

    }


    // approved multiple flag orders

    public function multipleApprovedFlagGrocery(Request $request)
    {
        $extra_message = '';
        $extra_info = [];
        $current_date = date('Y-m-d');
        $do_logout = "not_logout";
        DB::beginTransaction();
        try {

            $request_data = $request->data;

            foreach ($request_data as $key => $single_request) {


                $flag_data = FlagHistory::where('id', $single_request['flag_id'])->whereNull('unflaged_date')->first();

                if (is_null($flag_data)) {
                    return response()->json(['status' => false, 'message' => 'Someone Already Un-flag ']);
                }
                $flag_data->is_approved = 1;
                $flag_data->save();
                /*        FlagHistory::where('id', $id)
                            ->where('unflaged_by',0)
                            ->update([
                                "is_approved"=> 1
                            ]);*/

                $incident_count = JoeyPerformanceHistory::where('joey_id', $flag_data->joey_id)
                        ->where('flag_cat_id', $flag_data->flag_cat_id)
                        ->where('unflaged_by', '=', 0)
                        //->where('refresh_date', '>=', $current_date)
                        ->where(function ($query) use ($current_date) {
                            $query->where('refresh_date', '>=', $current_date)
                                ->orWhereNull('refresh_date');
                        })
                        ->count() + 1;

                // flag cat incident value should applied
                $flag_incident_values = CustomerFlagCategoryValues::where('category_ref_id', $flag_data->flag_cat_id)->first()->toArray();

                //Get Grand Parent Name
                $childParentName = CustomerFlagCategories::where('id', $flag_data->flag_cat_id)->first();
                $ParentName = CustomerFlagCategories::where('id', $childParentName->parent_id)->first();
                //$grandParentName = CustomerFlagCategories::where('id',$ParentName->parent_id)->first();

                // geting incident label
                $incident_label = '';
                $incident_label_finance = '';
                $rating_label = '';
                $incident_id = 1;

                $refresh_date = $current_date;

                // checking the incident is on conclusion or not
                if ($incident_count < 4) // for incident value
                {

                    $incident_id = $flag_incident_values['incident_' . $incident_count . '_ref_id'];

                    $finance_incident_value = $flag_incident_values['finance_incident_' . $incident_count];
                    $finance_incident_operator = $flag_incident_values['finance_incident_' . $incident_count . '_operator'];
                    $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                    $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                    $rating_value = $flag_incident_values['rating_' . $incident_count];
                    $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                    $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                    $refresh_date = strval($flag_incident_values['refresh_rate_incident_' . $incident_count]);

                } elseif ($incident_count == 4) // for conclusion
                {
                    $incident_id = $flag_incident_values['conclusion_ref_id'];
                    $finance_incident_value = $flag_incident_values['finance_conclusion'];
                    $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                    $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                    $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                    $rating_value = $flag_incident_values['rating_' . $incident_count];
                    $rating_operator = $flag_incident_values['rating_' . $incident_count . '_operator'];
                    $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                    $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
                } else // for termination
                {
                    $incident_id = $flag_incident_values['conclusion_ref_id']; // this id for termination label
                    $finance_incident_value = $flag_incident_values['finance_conclusion'];
                    $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                    $incident_label = CustomerIncidents::where('id', $incident_id)->pluck('label')->first();
                    $incident_label_finance = '{"value":"' . $finance_incident_value . '","operator":"' . $finance_incident_operator . '"}';

                    $rating_value = $flag_incident_values['rating_4'];
                    $rating_operator = $flag_incident_values['rating_4_operator'];
                    $rating_label = '{"value":"' . $rating_value . '","operator":"' . $rating_operator . '"}';
                    $refresh_date = strval($flag_incident_values['refresh_rate_conclusion']);
                }

                // calculating refresh rate
                $RefreshRateValueLabels = JoeyPerformanceHistory::RefreshRateValueLabels;

                if (array_key_exists($refresh_date, $RefreshRateValueLabels)) // checking the value exist in labels
                {
                    $refresh_date = date('Y-m-d', strtotime($current_date . $RefreshRateValueLabels[$refresh_date]));
                } else {
                    $refresh_date = null;
                }

                $Joey_performance_history_data = JoeyPerformanceHistory::create([
                    'flag_history_ref_id' => $single_request['flag_id'],
                    'route_id' => $flag_data->route_id,
                    'joey_id' => $flag_data->joey_id,
                    'tracking_id' => $flag_data->tracking_id,
                    'merchant_order_num' => $flag_data->merchant_order_num,
                    'sprint_id' => $flag_data->sprint_id,
                    'hub_id' => $flag_data->hub_id,
                    'flag_cat_id' => $flag_data->flag_cat_id,
                    'flag_cat_name' => $flag_data->flag_cat_name,
                    'flaged_by' => $flag_data->flaged_by,
                    'portal_type' => 'dashboard',
                    'flagged_type' => $flag_data->flagged_type,
                    'incident_value_applied' => $incident_label,
                    'finance_incident_value_applied' => $incident_label_finance,
                    'rating_value' => $rating_label,
                    'refresh_date' => $refresh_date,
                    'order_type' => $flag_data->order_type
                ]);

                $lastJoeyTransaction = JoeyTransactions::where('joey_id', $flag_data->joey_id)->orderBy('transaction_id', 'desc')->first();

                $flagAmount = '';
                $balanceValue = '';
                if ($Joey_performance_history_data->JosnValuesDecode('finance')['operator'] == '-') {
                    $paymentType = '-Deduction';
                    $joeyPaymentType = 'penalty';
                    $flagAmount = -$Joey_performance_history_data->JosnValuesDecode('finance')['value'];
                    $balanceValue = (isset($lastJoeyTransaction->balance) ? $lastJoeyTransaction->balance : 0) - $Joey_performance_history_data->JosnValuesDecode('finance')['value'];
                } else {
                    $paymentType = '-Bonus';
                    $joeyPaymentType = 'bonus';
                    $flagAmount = +$Joey_performance_history_data->JosnValuesDecode('finance')['value'];
                    $balanceValue = (isset($lastJoeyTransaction->balance) ? $lastJoeyTransaction->balance : 0) + $Joey_performance_history_data->JosnValuesDecode('finance')['value'];
                }
                //Finance Transaction
                $transaction = FinancialTransactions::create([
                    'reference' => 'CR-' . $flag_data->sprint_id . $paymentType ?? null,
                    'description' => $ParentName->category_name,
                    'amount' => $flagAmount,
                    'merchant_order_num' => ($flag_data->merchant_order_num != null) ? $flag_data->merchant_order_num : null
                ]);

                //Joey Transaction
                $joeyTransactionsData = [
                    'transaction_id' => $transaction->id,
                    'joey_id' => $flag_data->joey_id,
                    'type' => $joeyPaymentType,
                    'payment_method' => null,
                    'distance' => null,
                    'duration' => null,
                    'date_identifier' => null,
                    'shift_id' => null,
                    'balance' => $balanceValue,
                ];
                JoeyTransactions::insert($joeyTransactionsData);
                $balance = $joeyTransactionsData['balance'];
                Joey::where('id', $flag_data->joey_id)->update(['balance' => $balance]);


                //checking logout condition push
                if ($incident_label != 'warning') {
                    $do_logout = "logout";
                }
                //Getting joeys details to send notification
                $joey_data = Joey::where('id', '=', $flag_data->joey_id)
                    ->first();
                if ($joey_data == null) {
                    return response()->json(['status' => false, 'message' => 'This order has no joey for flag']);
                }
                //base64 convert
                //$email = base64_encode ("abdul.basit@joeyco.com");
                $email = base64_encode($joey_data->email);

                $joey_flag = ["route_id" => $flag_data->route_id, "sprint_no" => $flag_data->sprint_id, "flag_name" => $flag_data->flag_cat_name];
                //Send mail to joey on assign flag
                $Joey_performance_history_data->sendFlagEmailToJoey($email, $joey_data, $joey_flag);

                if (!empty($joey_flag['route_id'])) {
                    $message = 'You are receiving this notification because Joeyco take action on you against this route number "' . $joey_flag['route_id'] . '" and marked flaged ' . $joey_flag['flag_name'];
                } else {
                    $message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                }
                //Checking condition phone num exist or not
                if ($joey_data->phone != null) {
                    //set message to send

                    $sid = "ACb414b973404343e8895b05d5be3cc056";
                    $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                    $twilio = new Client($sid, $token);
                    try {

                        $message_twilio = $twilio->messages
                            //->create(+17087362094, // to
                            ->create($joey_data->phone,
                                [
                                    "body" => $message,
                                    "from" => "+16479316176"
                                ]
                            );

                    } catch (\Exception $e) {
                        $extra_message = 'but we cannot send message due to invalid number';
                        $extra_info['twilio_code'] = $e->getCode();
                        $extra_info['twilio_message'] = $e->getMessage();
                    }
                }

                if (isset($joey_data->id)) {
                    //$deviceIds = UserDevice::where('user_id', 10080)->pluck('device_token');
                    $deviceIds = UserDevice::where('user_id', $joey_data->id)->where('is_deleted_at','=',0)->pluck('device_token');
                    $subject = 'Hi ' . $joey_data->first_name . ' ' . $joey_data->last_name;
                    //$message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                    Fcm::sendPush($subject, $message, 'flag', null, $deviceIds);
                    $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'flag'],
                        'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'flag', 'do_logout' => $do_logout]];
                    $createNotification = [
                        'user_id' => $joey_data->id,
                        'user_type' => 'Joey',
                        'notification' => $subject,
                        'notification_type' => 'flag',
                        'notification_data' => json_encode(["body" => $message]),
                        'payload' => json_encode($payload),
                        'is_silent' => 0,
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    UserNotification::create($createNotification);
                }


                // set login validation
                $login_validation = new JoyFlagLoginValidationsHandler();
                $login_validation->setValues($flag_data->joey_id, $incident_id, $Joey_performance_history_data->id);
                $login_validation->applyAction();

                // sending curl request
                // initing the curlRequst
                /* $curl = new  CurlRequestSend();
                 $curl->setHeader('Cross-origin-token','Cross-origin-token: NWZhZmRjZmRkMDI5MjkuMzEzNDEzNTA=')
                     ->setHost('https://finance.joeyco.com')
                     ->setMethod('post')
                     ->setUri('api/v1/payout-update-hendler');
                 // getting task id by tracking id
                 $merchantids = null ;
                 if(!empty($flag_data->merchant_order_num) && !is_null($flag_data->merchant_order_num))
                 {
                     $merchantids = MerchantIds::where('merchant_order_num',$flag_data->merchant_order_num)
                         ->whereNull('deleted_at')
                         ->orderBy('id', 'DESC')
                         ->first();
                 }
                 // now checking the order is in route or not
                 if(empty($flag_data->route_id)) // this block is used with out route orders
                 {
                     $curl->setData(
                         [
                             //'route_id' => $flag_data->route_id,
                             'task_id' => (isset($merchantids->task_id))? $merchantids->task_id: null,
                             'joey_id' => $flag_data->joey_id,
                             'update_type' => '2',
                             //'update_for' => 'route_orders',
                             'meta_data' => stripslashes('{"finance_incident_value_applied":'.$incident_label_finance.',"Joey_performance_history_id":'.$Joey_performance_history_data->id.'}')
                         ]
                     );
                 }
                 $finance_portal_response = $curl->send()
                     ->rawResponce();*/

            }
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Joey flag approved successfully ' . $extra_message . ' !', 'extra_info' => $extra_info]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }

    }

    /**
     * Get Approved Flag Orders
     */
    public function groceryApprovedFlagList()
    {

        $all_joeys_accept_selected = Joey::where('is_enabled', '=', 1)->where('deleted_at', null)->limit(10)->get();
        return backend_view('grocery-flag-orders.approved-list', compact('all_joeys_accept_selected'));

    }

    /**
     * Datatable Approved Order List
     */
    public function groceryApprovedFlagListData(Datatables $datatables, Request $request)
    {
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');


        $query = FlagHistory::whereNull('hub_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->where('is_approved', 1)
            ->whereNull('deleted_at');

        // filters
        if ($request->joeys != '') {
            $query->whereIn('joey_id', $request->joeys);
        }

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joeyName->FullName;
            })
            ->editColumn('flag_by', static function ($record) {
                return $record->flagByName->full_name;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->addColumn('action', static function ($record) {
                return backend_view('grocery-flag-orders.action', compact('record'));
            })
            ->make(true);

    }

    /**
     * Get Un-Approved Flag Orders
     */
    public function groceryUnApprovedFlagList()
    {
        $all_joeys_accept_selected = Joey::where('is_enabled', '=', 1)->where('deleted_at', null)->limit(10)->get();
        return backend_view('grocery-flag-orders.un-approved-list', compact('all_joeys_accept_selected'));
    }

    /**
     * Datatable Un-Approved Order List
     */
    public function groceryUnApprovedFlagListData(Datatables $datatables, Request $request)
    {
        $start_date = !empty($request->start_date) ? $request->start_date . " 00:00:00" : date("Y-m-d 00:00:00");
        $end_date = !empty($request->end_date) ? $request->end_date . " 23:59:59" : date("Y-m-d 23:59:59");
        $start_date_converted = ConvertTimeZone($start_date,'America/Toronto','UTC');
        $end_date_converted = ConvertTimeZone($end_date,'America/Toronto','UTC');

        $query = FlagHistory::whereNull('hub_id')
            ->whereBetween('created_at', [$start_date_converted, $end_date_converted])
            ->where('is_approved', 0)
            ->whereNull('unflaged_date');

        // filters
        if ($request->joeys != '') {
            $query->whereIn('joey_id', $request->joeys);
        }

        return $datatables->eloquent($query)
            ->addColumn('all', static function ($record) {
                return backend_view('grocery-flag-orders.check-box', compact('record'));
            })
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joeyName->FullName;
            })
            ->editColumn('flag_by', static function ($record) {
                return $record->flagByName->full_name;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->addColumn('attachment_path', static function ($record) {
                if (isset($record->Sprint->sprintTasksFlag)) {
                    $ids = $record->Sprint->sprintTasksFlag->id;
                    $sprint_confirmation = SprintConfirmation::where('task_id', $ids)->whereNull('deleted_at')->whereNotNull('attachment_path')->orderBy('id', 'desc')->get();
                    foreach ($sprint_confirmation as $sprint) {
                        if (isset($sprint)) {
                            return '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $sprint->attachment_path . '" />';
                        } else {
                            return '';
                        }
                    }
                }
                else{
                    return '';
                }
            })
            ->editColumn('joey_performance_status', static function ($record) {
                return backend_view('grocery-flag-orders.joey-performance-status-action', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('grocery-flag-orders.action', compact('record'));
            })
            ->make(true);

    }

    /**
     * Get Block Joeys By Flag
     */
    public function groceryBlockJoeyFlagList(Request $request)
    {
        return backend_view('grocery-flag-orders.block-joey-flag-list');
    }

    /**
     *
     * block flag joeys list
     */
    public function groceryBlockJoeyFlagListData(Datatables $datatables, Request $request)
    {
        $query = JoyFlagLoginValidations::orderby('created_at', 'asc')
            ->whereNull('deleted_at')
            ->get();
        $group_joeys_ids = [];
        foreach ($query as $data) {
            $group_joeys_ids[$data->joey_id] = $data->id;
        }

        $query = JoyFlagLoginValidations::join('joey_performance_history', 'joey_flag_login_validations.joey_performance_history_id', '=', 'joey_performance_history.id')
            ->select(['joey_flag_login_validations.*', 'joey_performance_history.incident_value_applied as incident_value', 'joey_performance_history.id as joey_performance_id'])
            ->where('joey_performance_history.order_type','grocery')
            ->whereIn('joey_flag_login_validations.id', $group_joeys_ids);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('joey_id', static function ($record) {
                return $record->joeyName->id;
            })
            ->editColumn('joey_name', static function ($record) {
                return $record->joeyName->FullName;
            })
            ->editColumn('joey_email', static function ($record) {
                return $record->joeyName->email;
            })
            ->editColumn('joey_phone', static function ($record) {
                return $record->joeyName->phone;
            })
            ->editColumn('created_at', static function ($record) {
                return ConvertTimeZone($record->created_at, 'UTC', 'America/Toronto');
            })
            ->editColumn('suspension_date', static function ($record) {
                // checking the date is exist
                if (!is_null($record->window_start) && !is_null($record->window_end)) {
                    return $record->window_start . ' to ' . $record->window_end;
                } else {
                    return 'Not defined';
                }

            })
            ->editColumn('incident_value', static function ($record) {
                return ucwords(str_replace("_", " ", $record->incident_value));
            })
            ->addColumn('action', static function ($record) {
                return backend_view('grocery-flag-orders.block-list-action', compact('record'));
            })
            ->make(true);
    }

    /**
     *
     * Blocked Joeys
     */
    public function groceryUnblockJoeyFlag($id)
    {

        $current_date = date("Y-m-d H:i:s");
        $remove = JoyFlagLoginValidations::where('joey_id', $id)
            ->whereNull('deleted_at')
            ->update(["deleted_at" => $current_date]);
        return response()->json(['status' => true, 'message' => 'Joey unblock successfully']);

    }
}
