<?php

namespace App\Http\Controllers\Backend;

use App\BoradlessDashboard;
use App\City;
use App\CtcVendor;
use App\CustomerSupportReturnNotes;
use App\Http\Requests\Backend\CustomerSupportOrderRequest;
use App\Locations;
use App\ReturnReattemptProcess;
use App\Sprint;
use App\SprintContact;
use App\SprintTaskHistory;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use DateTime;
use DateTimeZone;
class CustomerSupportController extends BackendController
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
		"145" => "Returned To Merchant",
        "146" => "Delivery Missorted, Incorrect Address",
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow');

    /**
     * Get Customer Support records
     */
    public function getIndex(CustomerSupportOrderRequest $request)
    {
        //Getting request Data
        /*$request_data = $request->all();
        //Getting request data after filer to set old data
        $old_request_data = $request_data;
        //Get current date
        $current_date = date('Y-m-d');
        //Get date from request
        $start_date = (isset($request_data['search_date']) ? $request_data['search_date'] : $current_date );*/

        //get date
        $check_date =date("Y-m-d", strtotime("-3 day")).' 00:00:00';

        //Set query for customer support data
        $return_reattempt_history = ReturnReattemptProcess::where('process_type', '=', 'customer_support')
            ->where('created_at' , '>', $check_date)
			->where('is_expired_updated', 0)
            ->where('deleted_at',null)
            ->get();
        $return_reattempt_history_count = ReturnReattemptProcess::where('process_type', '=', 'customer_support')
            ->where('created_at' , '>', $check_date)
			->where('is_expired_updated', 0)
            ->where('deleted_at',null)
            ->count();
        //Check condition for date filter
        /*if($start_date != 0)
        {
            $query->where(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"), 'like', $start_date . "%");

        }
        
        //Set data in variable for view
        $return_reattempt_history = $query->get();*/
        return backend_view('customer-support.index',compact('return_reattempt_history'/*,'old_request_data'*/,'return_reattempt_history_count'));
    }

   /**
    *  Get customer report count
    */

    public function getCustomerCount(CustomerSupportOrderRequest $request)
    {
        //get date
        $check_date =date("Y-m-d", strtotime("-3 day")).' 00:00:00';

        //Set query for customer support data
        $return_reattempt_history = ReturnReattemptProcess::where('process_type', '=', 'customer_support')
            ->where('created_at' , '>', $check_date)
			->where('is_expired_updated', 0)
            ->where('deleted_at',null)
            ->count();

        return array("count" =>$return_reattempt_history);
    }

    /**
     * Get history order confirmation.
     */
    public function getOrderHistory(CustomerSupportOrderRequest $request)
    {
        //Getting request Data
        $request_data = $request->all();
        //Getting request data after filer to set old data
        $old_request_data = $request_data;
        //Get current date
        $current_date = date('Y-m-d');
        //Get date from request
        $start_date = (isset($request_data['search_date']) ? $request_data['search_date'] : $current_date );
        //Get CTC vendors
        $ctc_vendor = CtcVendor::pluck('vendor_id')->toArray();
        //merging array for ctc montreal and ottawa vendors
        $vendor_ids = array_merge($ctc_vendor,[477282,477260]);
        //Set query for customer support data
        $query = ReturnReattemptProcess::whereNotNull('verified_by')
            ->join('sprint__sprints', 'return_and_reattempt_process_history.sprint_id', '=', 'sprint__sprints.id')
            //->whereIn('sprint__sprints.creator_id',$vendor_ids)
            ->whereNotIn('sprint__sprints.creator_id',[477171])
            ->distinct('return_and_reattempt_process_history.id')
            ->select('return_and_reattempt_process_history.*');
        //Check condition for date filter
        if($start_date == null)
        {
            return back()->with('error','Date Field Is Required');
        }
        else
        {
            $start_dt = new DateTime($start_date." 00:00:00", new DateTimezone('America/Toronto'));
            $start_dt->setTimeZone(new DateTimezone('UTC'));
            $start = $start_dt->format('Y-m-d H:i:s');

            $end_dt = new DateTime($start_date." 23:59:59", new DateTimezone('America/Toronto'));
            $end_dt->setTimeZone(new DateTimezone('UTC'));
            $end = $end_dt->format('Y-m-d H:i:s');

            $query->where('return_and_reattempt_process_history.created_at','>',$start)->where('return_and_reattempt_process_history.created_at','<',$end);
            //where(\DB::raw("CONVERT_TZ(return_and_reattempt_process_history.created_at,'UTC','America/Toronto')"), 'like', $start_date . "%");
        }
        //Set data in variable for view
        $return_reattempt_history = $query->get();

        return backend_view('customer-support.history',compact('return_reattempt_history','old_request_data'));
    }


    /**
     * order confirmation.
     */
    public function orderConfirtmation(Request $request)
    {
        $data= $request->all();

        ReturnReattemptProcess::where('id',$data['id'])
            ->update([
            'verified_by' => auth()->user()->id,
            'verified_at' => Carbon::now()->format('Y-m-d h:m:s'),
            'varify_note'=>$data['note'],
            'process_type' =>'routing_support'
            ]);

        $reattemptUser = ReturnReattemptProcess::find($data['id']);
        $admin = User::where('id','=', $reattemptUser->created_by)->first();
        $admin->sendApproveAddressEmail($reattemptUser);

        return redirect()->back()
            //->route('order-confirmation-list.index')
            ->with('success', 'Order Approved!');

    }

    /**
     * Transfer Order To Customer Support Function
     */
    public function reattemptOrderColumnUpdate(Request $request)
    {

        // update sprint address
        if ($request->type == 'customer_address') {

            $customMessages = [
                'lat.required' => 'Your selected address does not belongs to any lat long. Kindly select nearby address ! ',
                'lng.required'  => 'Your selected address does not belongs to any lat long. Kindly select nearby address ! ',
                'postalcode.required'  => 'Your selected address does not contain a Postal Code. Kindly select a nearby address !',
                'city_val.required'  => 'Your Selected address does not contain city name kindly select near by address !',
            ];

            // validation
            $validator = Validator::make($request->all(), [
                'val' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'postalcode' => 'required',
                'city_val' => 'required',
            ],$customMessages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            //Getting Data From Ajax Request
            $update_address = $request->val;
            //$latitude = str_replace(".", "", $request->lat);
            //$latitudes = (strlen($latitude) > 10) ? (int)substr($latitude, 0, 8) : (int)$latitude;
            //$longitude = str_replace(".", "", $request->lng);
            //$longitudes = (strlen($longitude) > 10) ? (int)substr($longitude, 0, 9) : (int)$longitude;

            $latitude = $request->lat * 1000000;
            $latitudes = (int)$latitude;

			$longi = $request->lng * 1000000;
            $longitudes = (int)$longi;

            $postal_code = $request->postalcode;
            $city = $request->city_val;


            $cities_data = City::where('name', $city)->first();

            if (empty($cities_data) || $city == ''  || $city == null) {
                return response()->json(['status' => false, 'message' => 'Wrong address please enter correct address']);
            }

            $sprint_addr = ReturnReattemptProcess::where('id', $request->id)->update([
                'customer_address' => $update_address,
                'postal_code' => $postal_code,
                'is_action_applied' => 1,
                'latitude' => $latitudes,
                'longitude' => $longitudes,
                'city_id' => $cities_data->id,
                'state_id' => $cities_data->state_id,
                'country_id' => $cities_data->country_id,
                'is_address_updated' => 1,
            ]);

            if ($sprint_addr == 1) // creating record update successfully responce
            {
                return response()->json(['status' => true, 'message' => 'Address updated successfully']);
            }

            return response()->json(['status' => false, 'message' => 'Something went wrong please try again in a bit later ']);


        }
        // update sprint contact
        if ($request->type == 'customer_phone') {


            $customMessages = [
                'val.required' => 'The phone number is required.! ',
                'val.regex' =>  'The phone number format is incorrect.! ',
                'val.min' =>  'The value contain at least 10  numbers.! ',

            ];

            // validation
            $validator = Validator::make($request->all(), [
                'val' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:14',
            ],$customMessages);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $update_number = convert_ca_number_standard($request->val);

//            $sprint_contact = SprintContact::where('id', $request->ids)
//                ->update([
//                    'phone' => $update_number
//                ]);
            $sprint_cont = ReturnReattemptProcess::where('id', $request->id)->update(['customer_phone' => $update_number ,'is_action_applied' => 1]);
            if ($sprint_cont == 1) // creating record update successfully responce
            {
                return response()->json(['status' => true, 'message' => 'Phone updated successfully']);
            }
            return response()->json(['status' => false, 'message' => 'Something went wrong please try again in a bit later ']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again in a bit later ']);
    }
	
	/**
     *
     * Expired Order History Function
     */
    public function expiredOrder()
    {
        $expiry_order = ReturnReattemptProcess::where('process_type', '=', 'customer_support')
		->where(function ($query) {
                $check_date =date("Y-m-d", strtotime("-3 day")).' 23:59:59';
                $query->where('created_at' , '<=', $check_date)
                    ->orWhere('is_expired_updated', '=', 1);
            })
            ->where('verified_by', '=' , null)
            ->where('deleted_at',null)
            ->get();

        return backend_view('customer-support.expired-order-index',compact('expiry_order'));
    }

    /**
     * Return Order Function
     *
        */
    public function returnOrder($id, Request $request)
    {
        // update sprint contact
        if (!empty($id)) {
            DB::beginTransaction();
            try {

            $current_date = date("Y-m-d H:i:s");

            //Getting Data From Sprint And Vendor for return order
            $sprint_update = Sprint::find($id);

            $vendor_data = $sprint_update->Vendor;
            //Update Sprint For Return Order
            $sprint_update->status_id = 145;
            $sprint_update->save();

            //update task for return order
            $task_update = Task::where('sprint_id', $id)
                ->update([
                    'status_id' => 145
                ]);

                $borderlessData = BoradlessDashboard::where('sprint_id', $id)->whereNull('deleted_at')->first();

                if (!empty($borderlessData))
                {
                    //update boradless_dashboard for return order
                    $borderless_update = BoradlessDashboard::where('sprint_id', $id)
                        ->update([
                            'task_status_id' => 145
                        ]);
                }



            /*$task_id = Task::where('sprint_id', $id)
                ->where('type', 'dropoff')
                ->orderBy('ordinal','asc')
                ->pluck('id')
                ->toArray();
            dd($task_id);*/
            //dd($sprint_update->get_latest_task()->id);
            $task_history = [
                'sprint__tasks_id' => $sprint_update->get_latest_task()->id,
                'sprint_id' => $sprint_update->id,
                'status_id' => 145
            ];

            /*inserting data*/
            SprintTaskHistory::create($task_history);
            //update Reattempt order for return order request
            $reattempt_update = ReturnReattemptProcess::where('id', $request->return_process_id)
                ->update([
                    'process_type' => 'return',
                    'is_processed' => 1,
                    'proceed_at' => $current_date
                ]);



                DB::commit();
                if ($sprint_update == true && $task_update > 0) {
                    return response()->json(['status' => true, 'message' => 'Order returned successfully']);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => 'Something went wrong ']);
            }

        }

        return response()->json(['status' => false, 'message' => 'Something went wrong please try again in a bit later ']);
    }

    /**
     * order confirmation.
     */
    public function addNotes(Request $request)
    {
        $data= $request->all();
        CustomerSupportReturnNotes::create([
            'note_body' => $data['note'],
            'rarph_ref_id' => $data['id'],
            'creator_id' => Auth::user()->id
        ]);

        return redirect()->back()
            //->route('order-confirmation-list.index')
            ->with('success', 'Notes Added Successfully');

    }

    /**
     * Notes Show Function
     *
     */
    public function showNotes(Request $request, $id)
    {
        $notes = CustomerSupportReturnNotes::where('rarph_ref_id', $id)->where('deleted_at', null)->get();
        return backend_view('customer-support.customer_support_notes', compact('notes'));
    }
	
	/**
     *
     * Returned Order Function
     */
    public function returnedOrder()
    {

        $returned_orders = ReturnReattemptProcess::where('process_type', '=', 'return')
            ->where('deleted_at',null)
            ->get();

        return backend_view('customer-support.returned-order-index',compact('returned_orders'));
    }

}
