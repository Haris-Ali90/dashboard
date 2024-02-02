<?php

namespace App\Http\Controllers\Backend;


use App\BoradlessDashboard;
use App\Classes\Fcm;
use App\Classes\CurlRequestSend;
use App\Classes\JoeyPayoutCalculationClone;
use App\Classes\JoyFlagLoginValidationsHandler;
use App\CTCEntry;
use App\CtcVendor;
use App\CustomerFlagCategories;
use App\FinanceVendorCity;
use App\FinanceVendorCityDetail;
use App\FinancialTransactions;
use App\FlagCategoryMetaData;
use App\CustomerFlagCategoryValues;
use App\FlagHistory;
use App\CustomerIncidents;
use App\HubZones;
use App\JoeyTransactions;
use App\JoeyPlans;
use App\OrderImage;
use App\SprintContact;
use App\SystemParameters;
use App\Vendor;
use DateTimeZone;
use DateTime;
use App\Http\Requests\Backend\UploadImageRequest;
use App\Joey;
use App\JoeyPerformanceHistory;
use App\JoeyRouteLocations;
use App\Reason;
use App\RouteHistory;
use App\UserDevice;
use App\UserNotification;
use Illuminate\Http\Request;
use App\AmazonEnteries;
use App\Claim;
use App\TaskHistory;
use App\SprintReattempt;
use App\MerchantIds;
use App\Sprint;
use App\Task;
use DB;
use Illuminate\Support\Facades\Auth;
use App\TrackingImageHistory;
use App\JoeyRoutes;
use App\JoeyLocation;
use App\SprintConfirmation;
use App\SprintTaskHistory;
use App\StatusMap;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;


class SearchOrdersController extends BackendController
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
        "255" => 'Order Delay',
        '147' => 'Scanned at Hub',
        '148' => 'Scanned at Hub and labelled',
        '149' => 'pick from hub',
        '150' => 'drop to other hub',
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow'
    );

    private $status_codes = [
        'completed'=>
            [
                "JCO_ORDER_DELIVERY_SUCCESS"=>17,
                "JCO_HAND_DELIEVERY" => 113,
                "JCO_DOOR_DELIVERY" => 114,
                "JCO_NEIGHBOUR_DELIVERY" => 116,
                "JCO_CONCIERGE_DELIVERY" => 117,
                "JCO_BACK_DOOR_DELIVERY" => 118,
                "JCO_OFFICE_CLOSED_DELIVERY" => 132,
                "JCO_DELIVER_GERRAGE" => 138,
                "JCO_DELIVER_FRONT_PORCH" => 139,
                "JCO_DEILVER_MAILROOM" => 144
            ],
        'return'=>
            [
                "JCO_ITEM_DAMAGED_INCOMPLETE" => 104,
                "JCO_ITEM_DAMAGED_RETURN" => 105,
                "JCO_CUSTOMER_UNAVAILABLE_DELIEVERY_RETURNED" => 106,
                "JCO_CUSTOMER_UNAVAILABLE_LEFT_VOICE" => 107,
                "JCO_CUSTOMER_UNAVAILABLE_ADDRESS" => 108,
                "JCO_CUSTOMER_UNAVAILABLE_PHONE" => 109,
                "JCO_HUB_DELIEVER_REDELIEVERY" => 110,
                "JCO_HUB_DELIEVER_RETURN" => 111,
                "JCO_ORDER_REDELIVER" => 112,
                "JCO_ORDER_RETURN_TO_HUB" => 131,
                "JCO_CUSTOMER_REFUSED_DELIVERY" => 135,
                "CLIENT_REQUEST_CANCEL_ORDER" => 136,
                "JCO_ON_WAY_PICKUP" => 101,
                "JCO_TO_BE_RE_ATTEMPTED_TOMORROW" => 155,
            ],

        'pickup'=>
            [
                "JCO_HUB_PICKUP"=>121
            ],

    ];
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
            "32"  => "Order accepted by Joey",
            "14"  => "Merchant accepted",
            "36"  => "Cancelled by JoeyCo",
            "124" => "At hub - processing",
            "38"  => "Draft",
            "18"  => "Delivery failed",
            "56"  => "Partially delivered",
            "17"  => "Delivery success",
            "68"  => "Joey is at dropoff location",
            "67"  => "Joey is at pickup location",
            "13"  => "At hub - processing",
            "16"  => "Joey failed to pickup order",
            "57"  => "Not all orders were picked up",
            "15"  => "Order is with Joey",
            "112" => "To be re-attempted",
            "131" => "Office closed - returned to hub",
            "125" => "Pickup at store - confirmed",
            "61"  => "Scheduled order",
            "37"  => "Customer cancelled the order",
            "34"  => "Customer is editting the order",
            "35"  => "Merchant cancelled the order",
            "42"  => "Merchant completed the order",
            "54"  => "Merchant declined the order",
            "33"  => "Merchant is editting the order",
            "29"  => "Merchant is unavailable",
            "24"  => "Looking for a Joey",
            "23"  => "Waiting for merchant(s) to accept",
            "28"  => "Order is with Joey",
            "133" => "Packages sorted",
            "55"  => "ONLINE PAYMENT EXPIRED",
            "12"  => "ONLINE PAYMENT FAILED",
            "53"  => "Waiting for customer to pay",
            "141" => "Lost package",
            "60"  => "Task failure",
            "255" => 'Order Delay',
            "145" => 'Returned To Merchant',
            "146" => "Delivery Missorted, Incorrect Address",
            '147' => 'Scanned at Hub',
            '148' => 'Scanned at Hub and labelled',
            '149' => 'pick from hub',
            '150' => 'drop to other hub',
            '153' => 'Miss sorted to be reattempt',
            '154' => 'Joey unable to complete the route',
            '155' => 'To be re-attempted tomorrow');
        return $statusid[$id];
    }

  public function get_trackingid(Request $request)
  {
          $user=[];

        if(!empty($request->input('tracking_id')))
        {

            $id=$request->input('tracking_id');
            // dd(id)
            $user= MerchantIds::join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')->
            join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id') 
             ->where('sprint__tasks.type','=','dropoff');

              // dd($user);
            
            $user=$user->whereNull('sprint__sprints.deleted_at')
               ->where('merchantids.tracking_id','=',$id)->orderBy('merchantids.id','DESC')->take(1)
               ->get(array("sprint__sprints.*",'merchantids.tracking_id'));
               
                if(empty($user))
                {
                    $user=[];
                }

        }

          return backend_view('searchorder',['data'=>$user]);
  }

  public function get_trackingorderdetails($sprintId, Request $request)
  {

// dd('user');
      $data = Auth::user();

      //        $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $data->statistics ;
//        $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
      $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

      $hubIds = HubZones::whereIn('zone_id', function ($query) use ($statistics_id) {
          $query->select(
              DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
          );
      })
          ->pluck('hub_id')
          ->toArray();

      $show_message = $request->message;
      if(!is_null($show_message))
      {
          $current_url  = $request->url();
          $query_string = http_build_query( $request->except(['message'] ) );
          return redirect($current_url.'?'.$query_string)
              ->with('alert-success', $show_message);
      }

      $result= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
          ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
          ->leftJoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
          ->leftJoin('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
          ->leftJoin('joeys','joeys.id','=','joey_routes.joey_id')
          ->join('locations','sprint__tasks.location_id','=','locations.id')
          ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
          ->leftJoin('vendors','creator_id','=','vendors.id')
          ->where('sprint__tasks.sprint_id','=',$sprintId)
          ->whereNull('joey_route_locations.deleted_at')
          ->orderBy('ordinal','DESC')->take(1)
          ->get(array('sprint__tasks.*','joey_routes.hub','joey_routes.id as route_id','joey_routes.date','locations.address','locations.suite','locations.postal_code','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email',
              'joeys.first_name as joey_firstname','joeys.id as joey_id',
              'joeys.last_name as joey_lastname','vendors.type as vendor_order_type','vendors.id as merchant_id','vendors.name as business_name','vendors.first_name as merchant_firstname','vendors.name as business_name','vendors.last_name as merchant_lastname','merchantids.scheduled_duetime'
          ,'joeys.id as joey_id','merchantids.tracking_id','joeys.phone as joey_contact','joey_route_locations.ordinal as stop_number','merchantids.merchant_order_num','merchantids.address_line2','sprint__sprints.creator_id','sprint__sprints.is_hub','sprint__sprints.joey_id as merchant_joey_id'));

      $i=0;

      $data = [];
      $sprint_id = 0;
      $order_type = ($result[0]->vendor_order_type == 'ecommerce')?'ecommerce':'grocery';
      foreach($result as $tasks){
          $sprint_id = $tasks->sprint_id;
          $status2 = array();
          $status = array();
          $status1 = array();
          $data[$i] =  $tasks;
          $taskHistory= TaskHistory::where('sprint_id','=',$tasks->sprint_id)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
              //->where('active','=',1)
              ->get(['status_id','created_at']);

          $returnTOHubDate = SprintReattempt::
          where('sprint_reattempts.sprint_id','=' ,$tasks->sprint_id)->orderBy('created_at')
              ->first();

          if(!empty($returnTOHubDate))
          {
              $taskHistoryre= TaskHistory::where('sprint_id','=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                  //->where('active','=',1)
                  ->get(['status_id','created_at']);

              foreach ($taskHistoryre as $history){

                  $status[$history->status_id]['id'] = $history->status_id;
                  if($history->status_id==13)
                  {
                      $status[$history->status_id]['description'] ='At hub - processing';
                  }
                  else
                  {
                      $status[$history->status_id]['description'] =$this->statusmap($history->status_id);
                  }
                  $status[$history->status_id]['created_at'] = $history->created_at;

              }

          }
          if(!empty($returnTOHubDate))
          {
              $returnTO2 = SprintReattempt::
              where('sprint_reattempts.sprint_id','=' , $returnTOHubDate->reattempt_of)->orderBy('created_at')
                  ->first();

              if(!empty($returnTO2))
              {
                  $taskHistoryre= TaskHistory::where('sprint_id','=',$returnTO2->reattempt_of)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                      //->where('active','=',1)
                      ->get(['status_id','created_at']);

                  foreach ($taskHistoryre as $history){

                      $status2[$history->status_id]['id'] = $history->status_id;
                      if($history->status_id==13)
                      {
                          $status2[$history->status_id]['description'] ='At hub - processing';
                      }
                      else
                      {
                          $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                      }
                      $status2[$history->status_id]['created_at'] = $history->created_at;

                  }

              }
          }

          //    dd($taskHistory);

          foreach ($taskHistory as $history){


              if (in_array($history->status_id, [61,13]) or in_array($history->status_id, [124,125])) {
                  $status1[$history->status_id]['id'] = $history->status_id;

                  if ($history->status_id == 13) {
                      $status1[$history->status_id]['description'] = 'At hub - processing';
                  } else {
                      $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                  }
                  $status1[$history->status_id]['created_at'] = $history->created_at;
              }
              else{
                  if ($history->created_at >= $tasks->route_date){
                      $status1[$history->status_id]['id'] = $history->status_id;

                      if ($history->status_id == 13) {
                          $status1[$history->status_id]['description'] = 'At hub - processing';
                      } else {
                          $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                      }
                      $status1[$history->status_id]['created_at'] = $history->created_at;
                  }

              }

          }

          if($status!=null)
          {
              $sort_key = array_column($status, 'created_at');
              array_multisort($sort_key, SORT_ASC, $status);
          }
          if($status1!=null)
          {
              $sort_key = array_column($status1, 'created_at');
              array_multisort($sort_key, SORT_ASC, $status1);
          }
          if($status2!=null)
          {
              $sort_key = array_column($status2, 'created_at');
              array_multisort($sort_key, SORT_ASC, $status2);
          }


          $data[$i]['status']= $status;
          $data[$i]['status1']= $status1;
          $data[$i]['status2']=$status2;
          $i++;
      }

        $reasons = Reason::all();

      //getting category ref id of dashboard portal
      /*$portal =  FlagCategoryMetaData::where('type', 'vendor_relation')
          ->where('value', $vendor_id)
          ->pluck('category_ref_id');*/
      //getting flag categories
      /*$flagCategories =  CustomerFlagCategories::whereIn('id', $portal)
          ->where('parent_id', 0)
          ->where('is_enable', 1)
          ->get();*/
      //getting flag categories
      $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
          ->where('is_enable', 1)
          ->whereNull('deleted_at')
          ->get();
      /*$CategoryId = $flagCategories->pluck('id');
      $flagSubCategories =  CustomerFlagCategories::whereIn('parent_id', $CategoryId)
          ->get();*/

      //getting joey performance flag
      $joey_flags_history = FlagHistory::where('sprint_id',$sprint_id)
          ->orderBy('id', 'DESC')
          ->whereNull('deleted_at')
          ->where('unflaged_by','=',0)
          ->get();
        $manualHistory=[];    
        if(isset($result[0])){
            $manualHistory=$this->getManualStatusData($result[0]->tracking_id);
        }    

        return backend_view('orderdetailswtracknigid',
            [
                'data'=>$data,
                'sprintId' => $sprintId,
                'reasons' => $reasons,
                'flagCategories' => $flagCategories,
//                'flagSubCategories' => $flagSubCategories,
                'joey_flags_history' => $joey_flags_history,
                'order_type' => $order_type,
                'manualHistory' => $manualHistory,
                'hubIds' => $hubIds,
            ]
        );
  }
  
  public function get_orderIdDetails($sprintId, Request $request)
    {

        $data = Auth::user();

        //        $statistics_id = (is_supper_admin())? implode(',',FinanceVendorCity::pluck('id')->toArray()) : $data->statistics ;
//        $statistics_id = ($statistics_id == '' || $statistics_id == null) ? 0: $statistics_id ;
        $statistics_id = implode(',',FinanceVendorCity::pluck('id')->toArray());

        $hubIds = HubZones::whereIn('zone_id', function ($query) use ($statistics_id) {
            $query->select(
                DB::raw('zone_id from zone_vendor_relationship where vendor_id in (select vendors_id from finance_vendor_city_relations_detail where vendor_city_realtions_id in (' . $statistics_id . ')) ')
            );
        })
            ->pluck('hub_id')
            ->toArray();

        $show_message = $request->message;
        if(!is_null($show_message))
        {
            $current_url  = $request->url();
            $query_string = http_build_query( $request->except(['message'] ) );
            return redirect($current_url.'?'.$query_string)
                ->with('alert-success', $show_message);
        }

        $result= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
            ->leftJoin('joeys','joeys.id','=','joey_routes.joey_id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
            ->leftJoin('vendors','creator_id','=','vendors.id')
            //->where('sprint__tasks.sprint_id','=',$sprintId)
            ->where('sprint__tasks.id','=',$sprintId)
            ->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal','DESC')->take(1)
            ->get(array('sprint__tasks.*','joey_routes.hub','joey_routes.id as route_id',\DB::raw("CONVERT_TZ(joey_routes.date,'UTC','America/Toronto') as route_date"),'locations.address','locations.suite','locations.postal_code','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email',
                'joeys.first_name as joey_firstname','joeys.id as joey_id',
                'joeys.last_name as joey_lastname','vendors.type as vendor_order_type','vendors.id as merchant_id','vendors.name as business_name','vendors.first_name as merchant_firstname','vendors.name as business_name','vendors.last_name as merchant_lastname','merchantids.scheduled_duetime'
            ,'joeys.id as joey_id','merchantids.tracking_id','joeys.phone as joey_contact','joey_route_locations.ordinal as stop_number','merchantids.merchant_order_num','merchantids.address_line2','sprint__sprints.creator_id','sprint__sprints.is_hub','sprint__sprints.joey_id as merchant_joey_id'));

        $i=0;

        $data = [];
        $sprint_id = 0;
        $order_type = ($result[0]->vendor_order_type == 'ecommerce')?'ecommerce':'grocery';
        foreach($result as $tasks){
            $sprint_id = $tasks->sprint_id;
            $status2 = array();
            $status = array();
            $status1 = array();
            $data[$i] =  $tasks;
            $taskHistory= TaskHistory::where('sprint_id','=',$tasks->sprint_id)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                //->where('active','=',1)
                ->get(['status_id',\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id','=' ,$tasks->sprint_id)->orderBy('created_at')
                ->first();

            if(!empty($returnTOHubDate))
            {
                $taskHistoryre= TaskHistory::where('sprint_id','=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                    //->where('active','=',1)
                    ->get(['status_id',\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

                foreach ($taskHistoryre as $history){

                    $status[$history->status_id]['id'] = $history->status_id;
                    if($history->status_id==13)
                    {
                        $status[$history->status_id]['description'] ='At hub - processing';
                    }
                    else
                    {
                        $status[$history->status_id]['description'] =$this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = $history->created_at;

                }

            }
            if(!empty($returnTOHubDate))
            {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id','=' , $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();

                if(!empty($returnTO2))
                {
                    $taskHistoryre= TaskHistory::where('sprint_id','=',$returnTO2->reattempt_of)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                        //->where('active','=',1)
                        ->get(['status_id',\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);

                    foreach ($taskHistoryre as $history){

                        $status2[$history->status_id]['id'] = $history->status_id;
                        if($history->status_id==13)
                        {
                            $status2[$history->status_id]['description'] ='At hub - processing';
                        }
                        else
                        {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = $history->created_at;

                    }

                }
            }

            //    dd($taskHistory);

            foreach ($taskHistory as $history){


                if (in_array($history->status_id, [61,13]) or in_array($history->status_id, [124,125])) {
                    $status1[$history->status_id]['id'] = $history->status_id;

                    if ($history->status_id == 13) {
                        $status1[$history->status_id]['description'] = 'At hub - processing';
                    } else {
                        $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                    }
                    $status1[$history->status_id]['created_at'] = $history->created_at;
                }
                else{
                    if ($history->created_at >= $tasks->route_date){
                        $status1[$history->status_id]['id'] = $history->status_id;

                        if ($history->status_id == 13) {
                            $status1[$history->status_id]['description'] = 'At hub - processing';
                        } else {
                            $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status1[$history->status_id]['created_at'] = $history->created_at;
                    }

                }

            }

            if($status!=null)
            {
                $sort_key = array_column($status, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status);
            }
            if($status1!=null)
            {
                $sort_key = array_column($status1, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status1);
            }
            if($status2!=null)
            {
                $sort_key = array_column($status2, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status2);
            }


            $data[$i]['status']= $status;
            $data[$i]['status1']= $status1;
            $data[$i]['status2']=$status2;
            $i++;
        }

        $reasons = Reason::all();

        //getting category ref id of dashboard portal
        /*$portal =  FlagCategoryMetaData::where('type', 'vendor_relation')
            ->where('value', $vendor_id)
            ->pluck('category_ref_id');*/
        //getting flag categories
        /*$flagCategories =  CustomerFlagCategories::whereIn('id', $portal)
            ->where('parent_id', 0)
            ->where('is_enable', 1)
            ->get();*/
        //getting flag categories
        $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
            ->where('is_enable', 1)
            ->whereNull('deleted_at')
            ->get();
        /*$CategoryId = $flagCategories->pluck('id');
        $flagSubCategories =  CustomerFlagCategories::whereIn('parent_id', $CategoryId)
            ->get();*/

        //getting joey performance flag
        $joey_flags_history = FlagHistory::where('sprint_id',$sprint_id)
            ->orderBy('id', 'DESC')
            ->whereNull('deleted_at')
            ->where('unflaged_by','=',0)
            ->get();
        $manualHistory=[];
        if(isset($result[0])){
            $manualHistory=$this->getManualStatusData($result[0]->tracking_id);
        }

        return backend_view('orderdetailswtracknigid',
            [
                'data'=>$data,
                'sprintId' => isset($data[0]['sprint_id']) ? $data[0]['sprint_id'] : '',
                'reasons' => $reasons,
                'flagCategories' => $flagCategories,
//                'flagSubCategories' => $flagSubCategories,
                'joey_flags_history' => $joey_flags_history,
                'order_type' => $order_type,
                'manualHistory' => $manualHistory,
                'hubIds' => $hubIds,
            ]
        );
    }
  
  public function getManualStatusData($tracking_id)
    {
        // $tracking_id = !empty($request->get('tracking_id')) ? $request->get('tracking_id') : null;

        $query = TrackingImageHistory::where('tracking_id', $tracking_id)->whereNotNull('tracking_id')->whereNull('plan_id')->whereNull('route_id')->orderBy('created_at','desc')->get();

        if(count($query)){
            foreach ($query as $key => $value) {

                $current_status = $query[$key]->status_id;
                if (isset($current_status))
                {
                    if ($current_status == 13) {
                        $query[$key]->status_id= "At hub Processing";
                    }else {
                        $query[$key]->status_id= self::$status[$current_status];
                    }
                }
                else
                {
                    $query[$key]->status_id = '';
                }

                if (isset($value->attachment_path)) {
                    // $query[$key]->attachment_path= '<img onClick="ShowLightBox(this);" style = "width:50px;height:50px" src = "' . $value->attachment_path . '" />';
                    $query[$key]->attachment_path=$value->attachment_path;

                } else {
                    $query[$key]->attachment_path= '';
                }
                if (isset($value->reason)) {
                    $query[$key]->reason_id= $value->reason->title;
                } else {
                    $query[$key]->reason_id= '';
                }
                if ($value->created_at) {
                    $created_at = new \DateTime($value->created_at, new \DateTimeZone('UTC'));
                    $created_at->setTimeZone(new \DateTimeZone('America/Toronto'));
                    $query[$key]->created_at= $created_at->format('Y-m-d H:i:s');
                } else {
                    $query[$key]->created_at= '';
                }
                if (isset($value->user)) {
                    $query[$key]->user_id= $value->user->full_name;
                } else {
                    $query[$key]->user_id= '';
                }

            }
        }
        return $query;
    }

  //Create Flag
    public function createFlag($flag_cat_id, Request $request)
    {
        DB::beginTransaction();
        try {
            // getting incident count by joey id and category id
            $incident_count = JoeyPerformanceHistory::where('joey_id',$request->joey_id)
                    ->where('flag_cat_id',$flag_cat_id)
                    ->where('unflaged_by','=',0)
                    ->count() + 1;

            // getting category data
            $flag_category = CustomerFlagCategories::where('id',$flag_cat_id)->first();

            // flag cat incident value should applied
            $flag_incident_values = CustomerFlagCategoryValues::where('category_ref_id',$flag_cat_id)->first()->toArray();

            // geting incident label
            $incident_label = '';
            $incident_label_finance = '';
            $rating_label = '';
            $incident_id = 1;

            // checking the incident is on conclusion or not
            if($incident_count < 4) // for incident value
            {

                $incident_id = $flag_incident_values['incident_'.$incident_count.'_ref_id'];

                $finance_incident_value = $flag_incident_values['finance_incident_'.$incident_count];
                $finance_incident_operator = $flag_incident_values['finance_incident_'.$incident_count.'_operator'];
                $incident_label = CustomerIncidents::where('id',$incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"'.$finance_incident_value.'","operator":"'.$finance_incident_operator.'"}';

                $rating_value = $flag_incident_values['rating_'.$incident_count];
                $rating_operator = $flag_incident_values['rating_'.$incident_count.'_operator'];
                $rating_label = '{"value":"'.$rating_value.'","operator":"'.$rating_operator.'"}';

            }
            elseif($incident_count == 4) // for conclusion
            {
                $incident_id = $flag_incident_values['conclusion_ref_id'];
                $finance_incident_value = $flag_incident_values['finance_conclusion'];
                $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                $incident_label = CustomerIncidents::where('id',$incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"'.$finance_incident_value.'","operator":"'.$finance_incident_operator.'"}';

                $rating_value = $flag_incident_values['rating_'.$incident_count];
                $rating_operator = $flag_incident_values['rating_'.$incident_count.'_operator'];
                $rating_label = '{"value":"'.$rating_value.'","operator":"'.$rating_operator.'"}';
            }
            else // for termination
            {
                $incident_id = 4; // this id for termination label
                $finance_incident_value = $flag_incident_values['finance_conclusion'];
                $finance_incident_operator = $flag_incident_values['finance_conclusion_operator'];
                $incident_label = CustomerIncidents::where('id',$incident_id)->pluck('label')->first();
                $incident_label_finance = '{"value":"'.$finance_incident_value.'","operator":"'.$finance_incident_operator.'"}';

                $rating_value = $flag_incident_values['rating_'.$incident_id];
                $rating_operator = $flag_incident_values['rating_'.$incident_id.'_operator'];
                $rating_label = '{"value":"'.$rating_value.'","operator":"'.$rating_operator.'"}';
                //dd([$incident_label_finance,$rating_label]);
            }

            //Mark Flag Against Joey
            $Joey_performance_history_data = JoeyPerformanceHistory::create([
                'joey_id' => $request->joey_id,
                'tracking_id' => $request->tracking_id,
                'sprint_id' => $request->sprint,
                'flag_cat_id' => $flag_category->id,
                'flag_cat_name' => $flag_category->category_name,
                'flaged_by' => Auth::user()->id,
                'portal_type' => 'dashboard',
                'incident_value_applied' => $incident_label,
                'finance_incident_value_applied' => $incident_label_finance,
                'rating_value' =>$rating_label
            ]);

            //Getting joeys details to send notification
            $joey_data = Joey::where('id','=',$request->joey_id)
                ->first();
				
			if ($joey_data == null)
			{
				return response()->json(['status' => false, 'message' => 'This order has no joey for flag']);
			}

            //base64 convert
            $email = base64_encode ($joey_data->email);

            //getting flag details
            $joey_flag = ["sprint_no"=> $request->sprint,"flag_name"=> $flag_category->category_name];
			

            //Sen mail to joey on assign flag
            /*$Joey_performance_history_data->sendFlagEmailToJoey($email,$joey_data,$joey_flag);*/

            //Checking condition phone num exist or not
            /*if ($joey_data->phone != null)
            {
                //set message to send
                $message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                $sid = "ACb414b973404343e8895b05d5be3cc056";
                $token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                $twilio = new Client($sid, $token);
                try {

                    $message = $twilio->messages
                        ->create($joey_data->phone, // to
                            [
                                "body" => $message,
                                "from" => "+16479316176"
                            ]
                        );

                } catch (\Exception $e) {
                    echo $e->getCode() . ' : ' . $e->getMessage() . "<br>";
                }
            }*/

            /*if (isset($joey_data->id)) {
                $deviceIds = UserDevice::where('user_id', 10080)->where('is_deleted_at', 0)->pluck('device_token');
                $subject = 'Hi '.$joey_data->nickname;
                $message = 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'];
                Fcm::sendPush($subject, $message, 'itinerary', null, $deviceIds);
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
            }*/


            // set login validation
            $login_validation = new JoyFlagLoginValidationsHandler();
            $login_validation->setValues($request->joey_id,$incident_id);
            $login_validation->applyAction();

            //$deviceIds = UserDevice::where('user_id',10080)->where('is_deleted_at', 0)->pluck('device_token');
            //Push Notification
            //Fcm::sendPush('Hi'.$joey_data->nickname, 'You are receiving this notification because Joeyco take action on you against this order number "' . $joey_flag['sprint_no'] . '" and marked flaged ' . $joey_flag['flag_name'],null,null, $deviceIds);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'This order Flaged successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Something went wrong ']);
        }
    }

    //un-flag order
    public function unFlag($unFlag_id)
    {
        //getting data for un-flag order
        $unflag = JoeyPerformanceHistory::find($unFlag_id);

        //Getting joeys details to send notification
        $joey_data = Joey::where('id','=',$unflag->joey_id)
            ->first();

        //checking condition data exist or not
        if (is_null($unflag))
        {
            return redirect()->back()
                ->with('alert-danger', 'The id does`nt exist');
        }

        //Update Sprint For Return Order
        $unflag->unflaged_by = Auth::user()->id;
        $unflag->unflaged_date = date('Y-m-d H:i:s');
        $unflag->save();

        //base64 convert email
        $email = base64_encode ($joey_data->email);

        //getting flag details
        $joey_flag = ["sprint_no"=> $unflag->sprint_id,"flag_name"=> $unflag->flag_cat_name];

        //Mail send to joeys on un-flag
        /*$unflag->sendUnFlagEmailToJoey($email,$joey_data,$joey_flag);*/

        //Checking condition phone num exist or not
        /*if ($joey_data->phone != null)
        {
            $message = 'You are receiving this sms because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
            $sid = "AC31720b1363a6a2918a0b8553b5d35e74";
            $token = "431aa218618f557a8a8f7b76b21d0a8d";
            $twilio = new Client($sid, $token);
            try {

                $message = $twilio->messages
                    ->create($joey_data->phone, // to
                        [
                            "body" => $message,
                            "from" => "+14154960655"
                            //16477990253
                        ]
                    );

            } catch (\Exception $e) {
                echo $e->getCode() . ' : ' . $e->getMessage() . "<br>";
            }
        }*/

        /*if (isset($joey_data->id)) {
            $deviceIds = UserDevice::where('user_id', 10080)->where('is_deleted_at', 0)->pluck('device_token');
            $subject = 'Hi '.$joey_data->nickname;
            $message = 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".';
            Fcm::sendPush($subject, $message, 'itinerary', null, $deviceIds);
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
        }*/

        //$deviceIds = UserDevice::where('user_id',10080)->where('is_deleted_at', 0)->pluck('device_token');
        //Push Notification
        //Fcm::sendPush('Hi '.$joey_data->nickname, 'You are receiving this notification because Joeyco remove flag "'.$joey_flag['flag_name'].'" on you against this order number "' . $joey_flag['sprint_no'] . '".',null,null, $deviceIds);

        return redirect()->back()
            ->with('alert-success', 'This order is un-flag successfully');

    }

  public function updatestatus(Request $request){

    $sprint_id=$request->get('sprint_id');
      $statusId=$request->get('statusId');
      // $user= Auth::user();
      $task=MerchantIds::join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')->
      join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
      ->where('sprint__sprints.id','=',$sprint_id)->
      where('sprint__tasks.type','=','dropoff')->
          whereNull('sprint__tasks.deleted_at')->
          whereNull('sprint__sprints.deleted_at')
          ->orderby('sprint__sprints.id','DESC')->first(['merchantids.tracking_id','merchantids.task_id','creator_id','sprint__tasks.sprint_id']);

      $statistics_id = FinanceVendorCity::pluck('id')->toArray();

      $gettingVendorId = FinanceVendorCityDetail::whereIn('vendor_city_realtions_id', $statistics_id)
          ->pluck('vendors_id')
          ->toArray();

    //   dd($gettingVendorId, $task->creator_id);




      if (!in_array($task->creator_id, $gettingVendorId)) {
          return redirect()->back()->with('error', 'You don`t have permission to update the status of this order');
      }

      //entry into route_history
      $status = '';

      if(in_array($statusId,$this->status_codes['completed']))
      {
          $status = 2;

      }elseif (in_array($statusId,$this->status_codes['return'])){
          $status = 4;
      }
      elseif (in_array($statusId,$this->status_codes['pickup'])){
          $status = 3;
      }



      $route =JoeyRouteLocations::join('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
          ->where('joey_route_locations.task_id','=',$task->task_id)
          ->first(['joey_route_locations.id','joey_route_locations.route_id','joey_routes.joey_id','joey_route_locations.ordinal','joey_route_locations.task_id']);


      if(!empty($status)) {
          if(!empty($route)){
              $routehistory=new RouteHistory();
              $routehistory->route_id=$route->route_id;
              $routehistory->joey_id=$route->joey_id;
              $routehistory->status=$status;
              $routehistory->route_location_id=$route->id;
              $routehistory->task_id=$route->task_id;
              $routehistory->ordinal=$route->ordinal;
              $routehistory->type='Manual';
              $routehistory->updated_by=Auth::guard('web')->user()->id;
              $routehistory->save();

              if (isset($route->joey_id)) {
                  $deviceIds = UserDevice::where('user_id', $route->joey_id)->where('is_deleted_at', 0)->pluck('device_token');
                  $subject = 'R-' . $route->route_id . '-' . $route->ordinal;
                  $message = 'Your order status has been changed to ' . $this->statusmap($request->get('statusId'));
                  Fcm::sendPush($subject, $message, 'ecommerce', null, $deviceIds);
                  $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'ecommerce'],
                      'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'ecommerce']];
                  $createNotification = [
                      'user_id' => $route->joey_id,
                      'user_type' => 'Joey',
                      'notification' => $subject,
                      'notification_type' => 'ecommerce',
                      'notification_data' => json_encode(["body" => $message]),
                      'payload' => json_encode($payload),
                      'is_silent' => 0,
                      'is_read' => 0,
                      'created_at' => date('Y-m-d H:i:s')
                  ];
                  UserNotification::create($createNotification);
              }
          }
      }

      if(!empty($task))
      {
          $requestData['order_id'] = $task->sprint_id;
          $ctc_vendor_id= CtcVendor::where('vendor_id','=',$task->creator_id)->first();
      $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',125)->first();
          if($taskhistory) {
              if ($taskhistory->status_id == $statusId) {
                  return back()->with('success', 'Status Updated Successfully!');
              }
          }
          if($statusId==124 && !empty($ctc_vendor_id))
          {
              $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',125)->first();
              if($taskhistory==null)
              {
                  $pickupstoretime_date=new \DateTime();
                  $pickupstoretime_date->modify('-2 minutes');

                  $taskhistory=new TaskHistory();
                  $taskhistory->sprint_id=$requestData['order_id'];
                  $taskhistory->sprint__tasks_id=$task->task_id;
                  // $taskhistory->user_email=$user->email;
                  // $taskhistory->domain_name='routing';
                  $taskhistory->status_id=125;
                  $taskhistory->date = $pickupstoretime_date->format('Y-m-d H:i:s');
                  $taskhistory->created_at = $pickupstoretime_date->format('Y-m-d H:i:s');
                  $taskhistory->save();
              }

          }

          $delivery_status = [17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136];
          //[17,118,117,107,108,111,113,114,116];
          if (in_array($statusId, $delivery_status))
          {

              $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',121)->first();
              if($taskhistory==null)
              {
                  $pickuptime_date=new \DateTime();
                  $pickuptime_date->modify('-2 minutes');

                  $taskhistory=new TaskHistory();
                  $taskhistory->sprint_id=$requestData['order_id'];
                  $taskhistory->sprint__tasks_id=$task->task_id;
                  // $taskhistory->user_email=$user->email;
                  // $taskhistory->domain_name='routing';
                  $taskhistory->status_id=121;
                  $taskhistory->date=$pickuptime_date->format('Y-m-d H:i:s');
                  $taskhistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                  $taskhistory->save();

                  if(!empty($route)){

                      $routehistory=new RouteHistory();
                      $routehistory->route_id=$route->route_id;
                      $routehistory->joey_id=$route->joey_id;
                      $routehistory->status=3;
                      $routehistory->route_location_id=$route->id;
                      $routehistory->task_id=$route->task_id;
                      $routehistory->ordinal=$route->ordinal;
                      $routehistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                      $routehistory->updated_at=$pickuptime_date->format('Y-m-d H:i:s');
                      $routehistory->type='Manual';
                      $routehistory->updated_by=Auth::guard('web')->user()->id;

                      $routehistory->save();

                  }

                  $this->updateAmazonEntry(121,$requestData['order_id']);
                  $this->updateBorderLessDashboard(121,$requestData['order_id']);
                  $this->updateCTCEntry(121,$requestData['order_id']);
                  $this->updateClaims(121,$requestData['order_id']);
              }

          }
          Sprint::where('id','=',$requestData['order_id'])->update(['status_id'=>$statusId]);
          BoradlessDashboard::where('sprint_id','=',$requestData['order_id'])->update(['task_status_id'=>$statusId]);
          Task::where('id','=',$task->task_id)->update(['status_id'=>$statusId]);

          $taskhistory=new TaskHistory();
          $taskhistory->sprint_id=$requestData['order_id'];
          $taskhistory->sprint__tasks_id=$task->task_id;
          // $taskhistory->user_email=$user->email;
          // $taskhistory->domain_name='routing';
          $taskhistory->status_id=$statusId;
          $taskhistory->date=date('Y-m-d H:i:s');
          $taskhistory->created_at=date('Y-m-d H:i:s');
          $taskhistory->save();
             // calling amazon update entry function 
             $this->updateAmazonEntry($statusId,$requestData['order_id']);
          $this->updateBorderLessDashboard($statusId,$requestData['order_id']);
          $this->updateCTCEntry($statusId,$requestData['order_id']);
          $this->updateClaims($statusId,$requestData['order_id']);

          $createData = [
              'tracking_id' =>$task->tracking_id,
              'status_id' => $request->get('statusId'),
              'user_id' => auth()->user()->id,
              'domain' => 'dashboard'
          ];
          TrackingImageHistory::create($createData);


          //webhook work
          $status_arr = [121,17,113,114,116,117,118,132,138,139,144,101,102,103,104,105,106,107,108,109,110,111,112,131,135,136,143];
          if($task->creator_id == 477625 || $task->creator_id == 477633 || $task->creator_id == 477635){

              if(in_array($request->get('statusId'),$status_arr)){
                  $client_id = 'sb-646b6a39-bf8d-4453-93d7-209c90cfa646!b106018|it-rt-cpi-prod-ev6oz563!b56186';
                  $url_token = 'https://cpi-prod-ev6oz563.authentication.us10.hana.ondemand.com/oauth/token';
                  $client_secret = 'b96311a2-af61-48de-b8fd-873a2718622b$kbc8vB_csYmne3vjCdH3GMKGsrFkMnZzc3EJV39kD74=';
                  $curl = curl_init();
                  curl_setopt_array($curl, array(
                      CURLOPT_URL => "$url_token",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYHOST =>false,
                      CURLOPT_SSL_VERIFYPEER => false,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=".$client_id."&client_secret=".$client_secret,
                      CURLOPT_HTTPHEADER => array(
                          "content-type: application/x-www-form-urlencoded"
                      ),
                  ));
                  $data = curl_exec($curl);
                  $data =json_decode($data);
                  $curl = curl_init();
                  curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://cpi-prod-ev6oz563.it-cpi019-rt.cfapps.us10-002.hana.ondemand.com/http/prod/joeyco/webhook',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                       "tracking_id": "'.$task->tracking_id.'",
                       "status_id": "'.$request->get('statusId').'",
                       "description": "'.self::$status[$request->get('statusId')].'",
                       "timestamp": "'.strtotime(date('Y-m-d H:i:s')).'"
                   }',
                      CURLOPT_HTTPHEADER => array(
                          'Authorization: Bearer '.$data->access_token,
                          'Content-Type: application/json',
                          'Cookie: sap-usercontext=sap-client=100'
                      ),
                  ));
                  $response = curl_exec($curl);

                  curl_close($curl);
              }
          }


      }



        return back()->with('success','Status Updated Successfully!');
  }
public function get_multipletrackingidTest(Request $request)
    {


        $tracking_ids=trim($request->input('tracking_id'));
        $merchant_order_no=trim($request->input('merchant_order_no'));
        $phone_no=trim($request->input('phone_no'));
        $orders=[];
        if(!empty($tracking_ids) || !empty($merchant_order_no) || !empty($phone_no) )
        {
            // dd('adsa');
            $user= MerchantIds::join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')
                ->join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
                ->join('locations','sprint__tasks.location_id','=','locations.id')
                ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
                ->where('sprint__tasks.type','=','dropoff')
                ->whereNull('sprint__sprints.deleted_at')
                ->whereNotNull('merchantids.tracking_id');

            if(!empty($tracking_ids))
            {

                if (strpos($tracking_ids,',') !== false) {

                    $id=explode(",",$tracking_ids);
                }
                else
                {
                    $id=explode("\n",$tracking_ids);

                }

                $i=0;
                $ids=[];
                foreach($id as $trackingid)
                {

                    if(!empty(trim($trackingid)))
                    {

                        $pattern = "/^[a-zA-Z0-9@#$&*_-]*/i";
                        preg_match($pattern,trim($trackingid),$matche);
                        $ids[$i]= $matche[0];
                        $i++;
                    }

                }
                //dd(!empty($ids));
                if(!empty($ids))
                {

                    $user=$user->whereIn('merchantids.tracking_id',$ids);

                }
            }


            if(!empty($merchant_order_no))
            {
                if(!empty($merchant_order_no))
                {
                    if (strpos($merchant_order_no,',') !== false) {

                        $merchant_order_no=explode(",",$merchant_order_no);
                    }
                    else
                    {
                        $merchant_order_no=explode("\n",$merchant_order_no);

                    }
                    $i=0;
                    $ids=[];
                    foreach($merchant_order_no as $id)
                    {
                        if(!empty(trim($id)))
                        {
                            $merchant_orders_no[$i]=trim($id);
                            $i++;
                        }

                    }

                    if(!empty($merchant_orders_no))
                    {
                        $user=$user->whereIn('merchantids.merchant_order_num',$merchant_orders_no);
                    }
                }

            }
            if(!empty($phone_no))
            {
                if(!empty($phone_no))
                {
                    if (strpos($phone_no,',') !== false) {

                        $phone_no=explode(",",$phone_no);
                    }
                    else
                    {
                        $phone_no=explode("\n",$phone_no);

                    }
                    $i=0;
                    $customers_phone_no=[];
                    foreach($phone_no as $id)
                    {
                        if(!empty(trim($id)))
                        {

                            $customers_phone_no[$i]=(str_contains(trim($id), '+') )? trim($id) : "+".trim($id);

                            $i++;
                        }

                    }

                    if(!empty($customers_phone_no))
                    {
                        $user=$user->whereIn('sprint__contacts.phone',$customers_phone_no);
                    }
                }

            }

            $orders=$user->orderBy('merchantids.id','DESC')
                ->get(array("sprint__sprints.id",'sprint__sprints.creator_id','sprint__sprints.status_id',\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') as created_at"),'merchantids.tracking_id','merchantids.merchant_order_num','sprint__contacts.phone','locations.address','merchantids.address_line2'));

            $i=0;

            foreach($orders as $order)
            {

                if($orders[$i]->status_id==17 && $orders[$i]->creator_id!=477260 && $orders[$i]->creator_id!=477282 )
                {

                    $status_history=TaskHistory::where('sprint_id','=',$orders[$i]->id)->
                    //  where('status_id','!=',17)->
                    whereIn('status_id',[114,116,117,118,132,138,139,144,113])->
                    orderby('id','DESC')->
                    first();

                    if(!empty($status_history))
                    {
                        $orders[$i]->status_id=$status_history->status_id;
                    }


                }
                $i++;
            }


            if(empty($orders))
            {
                $orders=[];
            }

        }

        return backend_view('multiplesearchordertest',['data'=>$orders]);
    }

  public function updatestatustest(Request $request){

    $sprint_id=$request->get('sprint_id');
      $statusId=$request->get('statusId');
      // $user= Auth::user();
      $task=MerchantIds::join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')->
      join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
      ->where('sprint__sprints.id','=',$sprint_id)->
      where('sprint__tasks.type','=','dropoff')->
          whereNull('sprint__tasks.deleted_at')->
          whereNull('sprint__sprints.deleted_at')
          ->orderby('sprint__sprints.id','DESC')->first(['merchantids.tracking_id','merchantids.task_id','creator_id','sprint__tasks.sprint_id']);

      $statistics_id = FinanceVendorCity::pluck('id')->toArray();

      $gettingVendorId = FinanceVendorCityDetail::whereIn('vendor_city_realtions_id', $statistics_id)
          ->pluck('vendors_id')
          ->toArray();

      if (!in_array($task->creator_id, $gettingVendorId)) {
          return redirect()->back()->with('error', 'You don`t have permission to update the status of this order');
      }

      //entry into route_history
      $status = '';

      if(in_array($statusId,$this->status_codes['completed']))
      {
          $status = 2;

      }elseif (in_array($statusId,$this->status_codes['return'])){
          $status = 4;
      }
      elseif (in_array($statusId,$this->status_codes['pickup'])){
          $status = 3;
      }



      $route =JoeyRouteLocations::join('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
          ->where('joey_route_locations.task_id','=',$task->task_id)
          ->first(['joey_route_locations.id','joey_route_locations.route_id','joey_routes.joey_id','joey_route_locations.ordinal','joey_route_locations.task_id']);


      if(!empty($status)) {
          if(!empty($route)){
              $routehistory=new RouteHistory();
              $routehistory->route_id=$route->route_id;
              $routehistory->joey_id=$route->joey_id;
              $routehistory->status=$status;
              $routehistory->route_location_id=$route->id;
              $routehistory->task_id=$route->task_id;
              $routehistory->ordinal=$route->ordinal;
              $routehistory->type='Manual';
              $routehistory->updated_by=Auth::guard('web')->user()->id;
              $routehistory->save();

              if (isset($route->joey_id)) {
                  $deviceIds = UserDevice::where('user_id', $route->joey_id)->where('is_deleted_at', 0)->pluck('device_token');
                  $subject = 'R-' . $route->route_id . '-' . $route->ordinal;
                  $message = 'Your order status has been changed to ' . $this->statusmap($request->get('statusId'));
                  Fcm::sendPush($subject, $message, 'ecommerce', null, $deviceIds);
                  $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'ecommerce'],
                      'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'ecommerce']];
                  $createNotification = [
                      'user_id' => $route->joey_id,
                      'user_type' => 'Joey',
                      'notification' => $subject,
                      'notification_type' => 'ecommerce',
                      'notification_data' => json_encode(["body" => $message]),
                      'payload' => json_encode($payload),
                      'is_silent' => 0,
                      'is_read' => 0,
                      'created_at' => date('Y-m-d H:i:s')
                  ];
                  UserNotification::create($createNotification);
              }
          }
      }

      if(!empty($task))
      {
          $requestData['order_id'] = $task->sprint_id;
          $ctc_vendor_id= CtcVendor::where('vendor_id','=',$task->creator_id)->first();
      $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',125)->first();
          if($taskhistory) {
              if ($taskhistory->status_id == $statusId) {
                  return back()->with('success', 'Status Updated Successfully!');
              }
          }
          if($statusId==124 && !empty($ctc_vendor_id))
          {
              $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',125)->first();
              if($taskhistory==null)
              {
                  $pickupstoretime_date=new \DateTime();
                  $pickupstoretime_date->modify('-2 minutes');

                  $taskhistory=new TaskHistory();
                  $taskhistory->sprint_id=$requestData['order_id'];
                  $taskhistory->sprint__tasks_id=$task->task_id;
                  // $taskhistory->user_email=$user->email;
                  // $taskhistory->domain_name='routing';
                  $taskhistory->status_id=125;
                  $taskhistory->date = $pickupstoretime_date->format('Y-m-d H:i:s');
                  $taskhistory->created_at = $pickupstoretime_date->format('Y-m-d H:i:s');
                  $taskhistory->save();
              }

          }

          $delivery_status = [17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136];
          //[17,118,117,107,108,111,113,114,116];
          if (in_array($statusId, $delivery_status))
          {

              $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',121)->first();
              if($taskhistory==null)
              {
                  $pickuptime_date=new \DateTime();
                  $pickuptime_date->modify('-2 minutes');

                  $taskhistory=new TaskHistory();
                  $taskhistory->sprint_id=$requestData['order_id'];
                  $taskhistory->sprint__tasks_id=$task->task_id;
                  // $taskhistory->user_email=$user->email;
                  // $taskhistory->domain_name='routing';
                  $taskhistory->status_id=121;
                  $taskhistory->date=$pickuptime_date->format('Y-m-d H:i:s');
                  $taskhistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                  $taskhistory->save();

                  if(!empty($route)){

                      $routehistory=new RouteHistory();
                      $routehistory->route_id=$route->route_id;
                      $routehistory->joey_id=$route->joey_id;
                      $routehistory->status=3;
                      $routehistory->route_location_id=$route->id;
                      $routehistory->task_id=$route->task_id;
                      $routehistory->ordinal=$route->ordinal;
                      $routehistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                      $routehistory->updated_at=$pickuptime_date->format('Y-m-d H:i:s');
                      $routehistory->type='Manual';
                      $routehistory->updated_by=Auth::guard('web')->user()->id;

                      $routehistory->save();

                  }

                  $this->updateAmazonEntry(121,$requestData['order_id']);
                  $this->updateBorderLessDashboard(121,$requestData['order_id']);
                  $this->updateCTCEntry(121,$requestData['order_id']);
                  $this->updateClaims(121,$requestData['order_id']);
              }

          }
          Sprint::where('id','=',$requestData['order_id'])->update(['status_id'=>$statusId]);
          Task::where('id','=',$task->task_id)->update(['status_id'=>$statusId]);

          $taskhistory=new TaskHistory();
          $taskhistory->sprint_id=$requestData['order_id'];
          $taskhistory->sprint__tasks_id=$task->task_id;
          // $taskhistory->user_email=$user->email;
          // $taskhistory->domain_name='routing';
          $taskhistory->status_id=$statusId;
          $taskhistory->date=date('Y-m-d H:i:s');
          $taskhistory->created_at=date('Y-m-d H:i:s');
          $taskhistory->save();
             // calling amazon update entry function 
             $this->updateAmazonEntry($statusId,$requestData['order_id']);
          $this->updateBorderLessDashboard($statusId,$requestData['order_id']);
          $this->updateCTCEntry($statusId,$requestData['order_id']);
          $this->updateClaims($statusId,$requestData['order_id']);

          $createData = [
              'tracking_id' =>$task->tracking_id,
              'status_id' => $request->get('statusId'),
              'user_id' => auth()->user()->id,
              'domain' => 'dashboard'
          ];
          TrackingImageHistory::create($createData);

          // send curl request to finance for trigger payout update handler
          if($status == 2 ||  $status == 4) // this block check the status is for update payout
          {
              // initing the curlRequst
              $curl = new  CurlRequestSend();
              $curl->setHeader('Cross-origin-token','Cross-origin-token: NWZhZmRjZmRkMDI5MjkuMzEzNDEzNTA=')
                    ->setHost('https://finance.joeyco.com')
                    ->setMethod('post')
                    ->setUri('api/v1/payout-update-hendler');
              // now checking the order is in route or not
              if(!empty($route))
              {
			
                  $curl->setData(
                      [
                          'route_id' => $route->route_id,
                          'task_id' => $route->task_id,
                          'joey_id' => $route->joey_id,
                          'update_type' => '0',
                          'update_for' => 'route_orders',
                          'meta_data' => '{"route_history_status":'.$status.'}'
                      ]
                  );
              }
              elseif(1==2) // this block is used with out  route orders
              {

              }

              $finance_portal_response = $curl->send()
                  ->rawResponce();

          }


      }
        return back()->with('success','Status Updated Successfully!');
  }


  public function updateAmazonEntry($status_id,$order_id,$imageUrl=null)
  {
              if($status_id==133)
              {
                    // Get amazon enteries data from tracking id and check if the data exist in database and if exist update the sort date of the tracking id and status of that tracking id.  
                    $amazon_enteries =AmazonEnteries::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
                    if($amazon_enteries!=null)
                    {
                        
                        $amazon_enteries->sorted_at=date('Y-m-d H:i:s');
                        $amazon_enteries->task_status_id=133;
                        $amazon_enteries->order_image=$imageUrl;
                        $amazon_enteries->save();

                    }
              }
              elseif($status_id==121)
              {
                $amazon_enteries =AmazonEnteries::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
                if($amazon_enteries!=null)
                {
                    $amazon_enteries->picked_up_at=date('Y-m-d H:i:s');
                    $amazon_enteries->task_status_id=121;
                    $amazon_enteries->order_image=$imageUrl;
                    $amazon_enteries->save();
    
                }
              }
              elseif(in_array($status_id,[17,113,114,116,117,118,132,138,139,144]))
              {
                $amazon_enteries =AmazonEnteries::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
                if($amazon_enteries!=null)
                {
                    $amazon_enteries->delivered_at=date('Y-m-d H:i:s');
                    $amazon_enteries->task_status_id=$status_id;
                    $amazon_enteries->order_image=$imageUrl;
                    $amazon_enteries->save();
    
                }
              }
              elseif(in_array($status_id,[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103,140]))
              {
                $amazon_enteries =AmazonEnteries::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
                if($amazon_enteries!=null)
                {
                    $amazon_enteries->returned_at=date('Y-m-d H:i:s');
                    $amazon_enteries->task_status_id=$status_id;
                    $amazon_enteries->order_image=$imageUrl;
                    $amazon_enteries->save();
    
                }
              }
      
  }

    public function updateBorderLessDashboard($status_id,$order_id,$imageUrl=null)
    {
        if ($status_id == 133) {
            // Get amazon enteries data from tracking id and check if the data exist in database and if exist update the sort date of the tracking id and status of that tracking id.
            $borderless_dashboard = BoradlessDashboard::where('sprint_id', '=', $order_id)->whereNull('deleted_at')->first();
            if ($borderless_dashboard != null) {

                $borderless_dashboard->sorted_at = date('Y-m-d H:i:s');
                $borderless_dashboard->task_status_id = 133;
                $borderless_dashboard->order_image = $imageUrl;
                $borderless_dashboard->save();

            }
        } elseif ($status_id == 121) {
            $borderless_dashboard = BoradlessDashboard::where('sprint_id', '=', $order_id)->whereNull('deleted_at')->first();
            if ($borderless_dashboard != null) {
                $borderless_dashboard->picked_up_at = date('Y-m-d H:i:s');
                $borderless_dashboard->task_status_id = 121;
                $borderless_dashboard->order_image = $imageUrl;
                $borderless_dashboard->save();

            }
        } elseif (in_array($status_id, [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])) {
			
            $borderless_dashboard = BoradlessDashboard::where('sprint_id', '=', $order_id)->whereNull('deleted_at')->first();
            if ($borderless_dashboard != null) {
                $borderless_dashboard->delivered_at = date('Y-m-d H:i:s');
                $borderless_dashboard->task_status_id = $status_id;
                $borderless_dashboard->order_image = $imageUrl;
                $borderless_dashboard->save();

            }
        } elseif (in_array($status_id, [104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 101, 102, 103, 140, 155])) {
			
            $borderless_dashboard = BoradlessDashboard::where('sprint_id', '=', $order_id)->whereNull('deleted_at')->first();
            if ($borderless_dashboard != null) {
                $borderless_dashboard->returned_at = date('Y-m-d H:i:s');
                $borderless_dashboard->task_status_id = $status_id;
                $borderless_dashboard->order_image = $imageUrl;
                $borderless_dashboard->save();

            }
        }
    }
    public function get_multipletrackingid(Request $request)
    {


        $tracking_ids=trim($request->input('tracking_id'));
        $merchant_order_no=trim($request->input('merchant_order_no'));
        $joeyco_order_num=trim($request->input('joeyco_order_num'));
        $phone_no=trim($request->input('phone_no'));
        $orders=[];
        if(!empty($tracking_ids) || !empty($merchant_order_no)|| !empty($joeyco_order_num) || !empty($phone_no) )
        {
           // dd('adsa');
            $user= Task::leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
                ->join('sprint__sprints','sprint__tasks.sprint_id','=','sprint__sprints.id')
                ->join('locations','sprint__tasks.location_id','=','locations.id')
                ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
                ->where('sprint__tasks.type','=','dropoff')
                ->whereNull('sprint__sprints.deleted_at');

            if(!empty($tracking_ids))
            {

                if (strpos($tracking_ids,',') !== false) {

                    $id=explode(",",$tracking_ids);
                }
                else
                {
                    $id=explode("\n",$tracking_ids);

                }

                $i=0;
                $ids=[];
                foreach($id as $trackingid)
                {

                    if(!empty(trim($trackingid)))
                    {

                        $pattern = "/^[a-zA-Z0-9@#$&*_-]*/i";
                        preg_match($pattern,trim($trackingid),$matche);
                        $ids[$i]= $matche[0];
                        $i++;
                    }

                }
                //dd(!empty($ids));
                if(!empty($ids))
                {

                    $user=$user->whereNotNull('merchantids.tracking_id')->whereIn('merchantids.tracking_id',$ids);

                }

            }


            if(!empty($merchant_order_no))
            {
                if(!empty($merchant_order_no))
                {
                    if (strpos($merchant_order_no,',') !== false) {

                        $merchant_order_no=explode(",",$merchant_order_no);
                    }
                    else
                    {
                        $merchant_order_no=explode("\n",$merchant_order_no);

                    }
                    $i=0;
                    $ids=[];
                    foreach($merchant_order_no as $id)
                    {

                        if(!empty(trim($id)))
                        {
                            $merchant_orders_no[$i]=trim($id);
                            $i++;
                        }

                    }

                    if(!empty($merchant_orders_no))
                    {
                        $user=$user->whereIn('merchantids.merchant_order_num',$merchant_orders_no);
                    }

                }

            }
            //Joeyco Order Number Data Filter
            if(!empty($joeyco_order_num))
            {
                if(!empty($joeyco_order_num))
                {
                    if (strpos($joeyco_order_num,',') !== false) {

                        $joeyco_order_num=explode(",",$joeyco_order_num);
                    }
                    else
                    {
                        $joeyco_order_num=explode("\n",$joeyco_order_num);

                    }
                    $i=0;
                    $ids=[];
                    foreach($joeyco_order_num as $id)
                    {
                        if (is_numeric($id) == true)
                        {
                            if(!empty(trim($id)))
                            {
                                $joeyco_order_num[$i] = trim($id);
                                $i++;
                            }
                        }
                        elseif (is_numeric($id)== false)
                        {
                            if(!empty(trim($id)))
                            {
                                $string = $id;
                                $array = array('CR-');
                                $id = str_replace($array,'', $string);
                                $joeyco_order_num[$i] = trim($id);

                                $i++;
                            }
                        }


                    }

                    if(!empty($joeyco_order_num))
                    {
                            $user=$user->whereIn('sprint__sprints.id',$joeyco_order_num);

                    }

                }

            }
            if(!empty($phone_no))
            {
                if(!empty($phone_no))
                {
                    if (strpos($phone_no,',') !== false) {

                        $phone_no=explode(",",$phone_no);
                    }
                    else
                    {
                        $phone_no=explode("\n",$phone_no);

                    }
                    $i=0;
                    $customers_phone_no=[];
                    foreach($phone_no as $id)
                    {
                        if(!empty(trim($id)))
                        {

                            $customers_phone_no[$i]=(str_contains(trim($id), '+') )? trim($id) : "+".trim($id);

                            $i++;
                        }

                    }

                    if(!empty($customers_phone_no))
                    {
                        $user=$user->whereIn('sprint__contacts.phone',$customers_phone_no);
                    }
                }

            }

            $orders=$user->orderBy('merchantids.id','DESC')
                ->get(array("sprint__sprints.id",'sprint__sprints.creator_id','sprint__sprints.status_id',\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') as created_at"),'merchantids.tracking_id','merchantids.merchant_order_num','sprint__contacts.phone','locations.address','merchantids.address_line2','sprint__tasks.id as sprint_task_id'));

            $i=0;

            foreach($orders as $order)
            {

                if($orders[$i]->status_id==17 && $orders[$i]->creator_id!=477260 && $orders[$i]->creator_id!=477282 )
                {

                    $status_history=TaskHistory::where('sprint_id','=',$orders[$i]->id)->
                    //  where('status_id','!=',17)->
                    whereIn('status_id',[114,116,117,118,132,138,139,144,113])->
                    orderby('id','DESC')->
                    first();

                    if(!empty($status_history))
                    {
                        $orders[$i]->status_id=$status_history->status_id;
                    }


                }
                $i++;
            }


            if(empty($orders))
            {
                $orders=[];
            }

        }

        return backend_view('multiplesearchorder',['data'=>$orders]);
    }

    public function get_multiOrderUpdates(Request $request)
    {
          return backend_view('multipleupdateorder',['data'=>[]]);
    }

  public function post_multiOrderUpdates(Request $request){

      $k=0;
      $trackingIdValidator = [];
      $user=[];
      $id = $request->input('tracking_id');
      if (strpos($id,',') !== false) {
          $id=explode(",",$id);

      }
      else
      {
          $id=explode("\n",$id);

      }

      $requestData['status_id']=$request->input('status_id');
      // $user= Auth::user();

      foreach($id as $trackingid){
          $pattern = "/^[a-zA-Z0-9@#$&*_-]*/i";
          preg_match($pattern,trim($trackingid),$match);
          $trackingid=$match[0];
          $task=MerchantIds::join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')->
          join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
              ->where('merchantids.tracking_id','=',$trackingid)->
              //->whereNull('deleted_at')->
              whereNull('sprint__tasks.deleted_at')->
              whereNull('sprint__sprints.deleted_at')
              ->orderby('sprint__sprints.id','DESC')->first(['merchantids.task_id','creator_id','sprint__tasks.sprint_id']);

          /*$data = Auth::user();
                     $statistics_id = explode(',', $data->statistics);
                     $statistics_id = $statistics_id;*/
          $statistics_id = FinanceVendorCity::pluck('id')->toArray();


          $gettingVendorId = FinanceVendorCityDetail::whereIn('vendor_city_realtions_id', $statistics_id)
              ->pluck('vendors_id')
              ->toArray();

          if (!in_array($task->creator_id, $gettingVendorId)) {
              //dd($trackingIdValidator,$trackingid);
              array_push($trackingIdValidator,$trackingid);
              continue;
          }

          if(empty($task)){
              continue;
          }

          //route history entry work

          $status = '';

          if(in_array($requestData['status_id'],$this->status_codes['completed']))
          {
              $status = 2;

          }elseif (in_array($requestData['status_id'],$this->status_codes['return'])){
              $status = 4;
          }
          elseif (in_array($requestData['status_id'],$this->status_codes['pickup'])){
              $status = 3;
          }


          $route =JoeyRouteLocations::join('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
              ->where('joey_route_locations.task_id','=',$task->task_id)
              ->first(['joey_route_locations.id','joey_route_locations.route_id','joey_routes.joey_id','joey_route_locations.ordinal','joey_route_locations.task_id']);

          if(!empty($route)){

              $routehistory=new RouteHistory();
              $routehistory->route_id=$route->route_id;
              $routehistory->joey_id=$route->joey_id;
              $routehistory->status=$status;
              $routehistory->route_location_id=$route->id;
              $routehistory->task_id=$route->task_id;
              $routehistory->ordinal=$route->ordinal;
              $routehistory->type='Manual';
              $routehistory->updated_by=Auth::guard('web')->user()->id;

              $routehistory->save();

              if (isset($route->joey_id)) {
                  $deviceIds = UserDevice::where('user_id', $route->joey_id)->where('is_deleted_at', 0)->pluck('device_token');
                  $subject = 'R-' . $route->route_id . '-' . $route->ordinal;
                  $message = 'Your order status has been changed to ' . $this->statusmap($request->input('status_id'));
                  Fcm::sendPush($subject, $message, 'ecommerce', null, $deviceIds);
                  $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'ecommerce'],
                      'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'ecommerce']];
                  $createNotification = [
                      'user_id' => $route->joey_id,
                      'user_type' => 'Joey',
                      'notification' => $subject,
                      'notification_type' => 'ecommerce',
                      'notification_data' => json_encode(["body" => $message]),
                      'payload' => json_encode($payload),
                      'is_silent' => 0,
                      'is_read' => 0,
                      'created_at' => date('Y-m-d H:i:s')
                  ];
                  UserNotification::create($createNotification);
              }
          }



          if(!empty($task->task_id)){
              $requestData['order_id'] = $task->sprint_id;
              $k=1;
               $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',125)->first();
              if($taskhistory) {
                  if ($taskhistory->status_id == $request->input('status_id')) {

                      continue;
                  }
              }
              $ctc_vendor_id= CtcVendor::where('vendor_id','=',$task->creator_id)->first();
              if($requestData['status_id']==124 && !empty($ctc_vendor_id))
              {
                  $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',125)->first();
                  if($taskhistory==null)
                  {

                      $pickupstoretime_date=new \DateTime();
                      $pickupstoretime_date->modify('-2 minutes');

                      $taskhistory=new TaskHistory();
                      $taskhistory->sprint_id=$requestData['order_id'];
                      $taskhistory->sprint__tasks_id=$task->task_id;
                      // $taskhistory->user_email=$user->email;
                      // $taskhistory->domain_name='routing';
                      $taskhistory->status_id=125;
                      $taskhistory->date = $pickupstoretime_date->format('Y-m-d H:i:s');
                      $taskhistory->created_at = $pickupstoretime_date->format('Y-m-d H:i:s');
                      $taskhistory->save();
                  }

              }

              $delivery_status = [17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136];

              if (in_array($requestData['status_id'], $delivery_status)) {

                  $taskhistory=TaskHistory::where('sprint_id','=',$requestData['order_id'])->where('status_id','=',121)->first();
                  if($taskhistory==null)
                  {

                      $pickuptime_date=new \DateTime();
                      $pickuptime_date->modify('-2 minutes');

                      $taskhistory=new TaskHistory();
                      $taskhistory->sprint_id=$requestData['order_id'];
                      $taskhistory->sprint__tasks_id=$task->task_id;
                      // $taskhistory->user_email=$user->email;
                      // $taskhistory->domain_name='routing';
                      $taskhistory->status_id=121;
                      $taskhistory->date=$pickuptime_date->format('Y-m-d H:i:s');
                      $taskhistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                      $taskhistory->save();

                      if(!empty($route)){

                          $routehistory=new RouteHistory();
                          $routehistory->route_id=$route->route_id;
                          $routehistory->joey_id=$route->joey_id;
                          $routehistory->status=3;
                          $routehistory->route_location_id=$route->id;
                          $routehistory->task_id=$route->task_id;
                          $routehistory->ordinal=$route->ordinal;
                          $routehistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                          $routehistory->updated_at=$pickuptime_date->format('Y-m-d H:i:s');
                          $routehistory->updated_by=Auth::guard('web')->user()->id;
                          $routehistory->type='Manual';
                          $routehistory->save();

                      }

                      $this->updateAmazonEntry(121,$requestData['order_id']);
                      $this->updateBorderLessDashboard(121,$requestData['order_id']);
                      $this->updateCTCEntry(121,$requestData['order_id']);
                      $this->updateClaims(121,$requestData['order_id']);

                  }

              }

              Sprint::where('id','=',$requestData['order_id'])->update(['status_id'=>$requestData['status_id']]);


              Task::where('sprint_id','=',$requestData['order_id'])->update(['status_id'=>$requestData['status_id']]);

              BoradlessDashboard::where('sprint_id','=',$requestData['order_id'])->where('task_id','=',$task->task_id)->update(['task_status_id'=>$requestData['status_id']]);

              $taskhistory=new TaskHistory();
              $taskhistory->sprint_id=$requestData['order_id'];
              $taskhistory->sprint__tasks_id=$task->task_id;
              // $taskhistory->user_email=$user->email;
              // $taskhistory->domain_name='routing';
              $taskhistory->status_id=$requestData['status_id'];
              $taskhistory->date=date('Y-m-d H:i:s');
              $taskhistory->created_at=date('Y-m-d H:i:s');
              $taskhistory->save();
                // calling amazon update entry function 
              $this->updateAmazonEntry($requestData['status_id'],$requestData['order_id']);
              $this->updateBorderLessDashboard($requestData['status_id'],$requestData['order_id']);
              $this->updateCTCEntry($requestData['status_id'],$requestData['order_id']);
              $this->updateClaims($requestData['status_id'],$requestData['order_id']);


              $createData = [
                  'tracking_id' => $trackingid,
                  'status_id' => $requestData['status_id'],
                  'user_id' => auth()->user()->id,
                  //'attachment_path' => $attachment_path,
                  // 'reason_id' => $postData['reason_id'],
                  'domain' => 'dashboard'
              ];
              TrackingImageHistory::create($createData);

              //webhook work
              $status_arr = [121,17,113,114,116,117,118,132,138,139,144,101,102,103,104,105,106,107,108,109,110,111,112,131,135,136,143];
              if($task->creator_id == 477625 || $task->creator_id == 477633 || $task->creator_id == 477635){

                  if(in_array($request->get('statusId'),$status_arr)){
                      $client_id = 'sb-646b6a39-bf8d-4453-93d7-209c90cfa646!b106018|it-rt-cpi-prod-ev6oz563!b56186';
                      $url_token = 'https://cpi-prod-ev6oz563.authentication.us10.hana.ondemand.com/oauth/token';
                      $client_secret = 'b96311a2-af61-48de-b8fd-873a2718622b$kbc8vB_csYmne3vjCdH3GMKGsrFkMnZzc3EJV39kD74=';
                      $curl = curl_init();
                      curl_setopt_array($curl, array(
                          CURLOPT_URL => "$url_token",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_SSL_VERIFYHOST =>false,
                          CURLOPT_SSL_VERIFYPEER => false,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=".$client_id."&client_secret=".$client_secret,
                          CURLOPT_HTTPHEADER => array(
                              "content-type: application/x-www-form-urlencoded"
                          ),
                      ));
                      $data = curl_exec($curl);
                      $data =json_decode($data);
                      $curl = curl_init();
                      curl_setopt_array($curl, array(
                          CURLOPT_URL => 'https://cpi-prod-ev6oz563.it-cpi019-rt.cfapps.us10-002.hana.ondemand.com/http/prod/joeyco/webhook',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>'{
                       "tracking_id": "'.$task->tracking_id.'",
                       "status_id": "'.$request->get('statusId').'",
                       "description": "'.self::$status[$request->get('statusId')].'",
                       "timestamp": "'.strtotime(date('Y-m-d H:i:s')).'"
                   }',
                          CURLOPT_HTTPHEADER => array(
                              'Authorization: Bearer '.$data->access_token,
                              'Content-Type: application/json',
                              'Cookie: sap-usercontext=sap-client=100'
                          ),
                      ));
                      $response = curl_exec($curl);

                      curl_close($curl);
                  }
              }



          }
      }
      if($k==0)
      {
          return back()->with('error','Invalid Tracking Id!');
      }

      if(count($trackingIdValidator) > 0)
      {
          $error_message = implode(", " , $trackingIdValidator);
          $returnMessage =  back()->with('error', "Some of the tracking ids status can't be updated due to permissoins issue kindly check this tracking ids '".$error_message." '  belongs to your hub ");
      }
      else
      {
          $returnMessage = back()->with('success', 'Status Updated Successfully!');
      }
      return $returnMessage;
            //return back()->with('success','Status Updated Successfully!');
            // return backend_view('multipleupdateorder',['data'=>$user]);
  }

    public function sprintImageUpdate(Request $request)
    {
        $taskId = $request->get('task_id');
        $imageId = $request->get('image_id');
        $type = $request->get('type');

        $task = Task::whereNull('deleted_at')->where('id', $taskId)->first();

        $image_base64 =  base64_encode(file_get_contents($_FILES['sprint_image']['tmp_name']));
        $data = ['image' =>  $image_base64];
        $response =  $this->sendData('POST', '/',  $data );

        if(!isset($response->url)) {
            session()->flash('alert-warning', 'File cannot be uploaded due to server error!');
            return Redirect::to('search/orders/trackingid/'.$task->sprint_id.'/details');
        }

        $attachment_path =   $response ->url;
        if($type == 'image'){
            SprintConfirmation::where('id', $imageId)->update(['attachment_path'=>$attachment_path]);
        }else{
            OrderImage::where('id', $imageId)->update(['image'=>$attachment_path]);
        }

        session()->flash('alert-success', 'Image updated successfully!');
        return Redirect::to('search/orders/trackingid/'.$task->sprint_id.'/details');

    }

	public function sprintImageUpload(UploadImageRequest $request)
    {
        $postData = $request->all();
        $wildForkVendorIds = [477625,477633,477635];
        $deliveredStatus = [17, 113, 114, 116, 117, 118, 132, 138, 139, 144];

		$image_base64 =  base64_encode(file_get_contents($_FILES['sprint_image']['tmp_name']));

        $task=MerchantIds::join('sprint__tasks','sprint__tasks.id','=','merchantids.task_id')
            ->join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->where('sprint__sprints.id','=',$postData['sprint_id'])
			->where('sprint__tasks.type','=','dropoff')
            ->first(['sprint__tasks.id','sprint__tasks.sprint_id','sprint__tasks.ordinal','sprint__sprints.creator_id','merchantids.tracking_id', 'merchantids.merchant_order_num']);

        $route_data=JoeyRoutes::join('joey_route_locations','joey_route_locations.route_id','=','joey_routes.id')
            ->where('joey_route_locations.task_id','=',$task->id)
            ->whereNull('joey_route_locations.deleted_at')
            ->first(['joey_route_locations.id','joey_routes.joey_id','joey_route_locations.route_id','joey_route_locations.ordinal']);

        if(empty($route_data)) {
            session()->flash('alert-warning', 'Joey not assigned yet. Image cannot be uploaded.!');
            return Redirect::to('search/orders/trackingid/'.$task->sprint_id.'/details');
        }
    	$taskhistory=TaskHistory::where('sprint_id','=',$postData['sprint_id'])->where('status_id','=',125)->first();
        if($taskhistory) {
            if ($taskhistory->status_id == $postData['status_id']) {

                session()->flash('alert-success', 'Image Uploaded');
                return Redirect::to('search/orders/trackingid/' . $task->sprint_id . '/details');
            }
        }
        $data = ['image' =>  $image_base64];//$base64Data];
        $response =  $this->sendData('POST', '/',  $data );
        // checking responce
        if(!isset($response->url))
        {
            session()->flash('alert-warning', 'File cannot be uploaded due to server error!');
            return Redirect::to('search/orders/trackingid/'.$task->sprint_id.'/details');
        }

        $attachment_path =   $response ->url;

        $status = '';

        if(in_array($postData['status_id'], $deliveredStatus)){

            // extra query data for calculation
            $extra_query_perams = [];
            $extra_query_perams['system_parameters'] = SystemParameters::getKeyValue(['gas','truck','hourly','tech']);
            $extra_query_perams['JoeysPlanTypes'] = JoeyPlans::JoeysPlanTypes;
            $extra_query_perams['route_id'] = $route_data->route_id;
            $extra_query_perams['default_plan'] = JoeyPlans::with(['PlanDetails'=>function($query){
                $query->orderBy('sorting_order','ASC');
            }])->where('plan_type','default|default_custom_routing|default_big_box')
                ->whereNull('joey_plans.deleted_at')
                ->first();

            $query = RouteHistory::join('sprint__tasks', 'route_history.task_id', '=', 'sprint__tasks.id')
                ->join('sprint__sprints', 'sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
                ->join('joeys', 'route_history.joey_id', '=', 'joeys.id')
                ->join('joey_routes', 'route_history.route_id', '=', 'joey_routes.id')
                ->join('joey_route_locations', 'route_history.route_id', '=', 'joey_route_locations.route_id')
                ->where('sprint__sprints.creator_type','vendor')
                ->where('route_history.joey_id','!=' ,null)
                ->where('route_history.route_id','=' ,$route_data->route_id)
                ->whereNull('joey_routes.deleted_at')
                ->whereNull('joey_route_locations.deleted_at')
                ->where('joeys.plan_id','!=' ,null)
                ->where('route_history.route_location_id','!=' ,null)
                ->select('route_history.*')
                ->groupBy('route_history.route_id')
                ->get();

            $return_data = JoeyPayoutCalculationClone::calculate($query,$extra_query_perams);

            $routeHistoryOrder = RouteHistory::where('task_id',$task->id)->first();
            if(isset($routeHistoryOrder->joey_id)){
                $joeyPickedTaskIds = RouteHistory::whereNotNull('task_id')->where('joey_id',$routeHistoryOrder->joey_id)->where('route_id',$routeHistoryOrder->route_id)->where('status', 3)->groupBy('task_id')->pluck('task_id');

                $joeyRouteLocation = JoeyRouteLocations::join('joey_routes','joey_route_locations.route_id','=','joey_routes.id')
                    ->where('joey_route_locations.task_id', $task->id)
                    ->whereNull('joey_route_locations.deleted_at')->first();

                $deliveredTasks = Task::whereIn('id', $joeyPickedTaskIds)->whereIn('status_id',[17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->get();
                $reattemptAndDeliveredTasks = Task::whereIn('id', $joeyPickedTaskIds)->whereIn('status_id',[17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 112, 155])->get();


                $totalJoeyPickedOrder = count($joeyPickedTaskIds);
                $deliveredCount = count($deliveredTasks)+1;
                $routeDeliveredAndReattempt = count($reattemptAndDeliveredTasks)+1;

//            dd($totalJoeyPickedOrder, $deliveredCount, $routeDeliveredAndReattempt);

                if($totalJoeyPickedOrder == $deliveredCount || $totalJoeyPickedOrder == $routeDeliveredAndReattempt){
                    $financialTransaction =FinancialTransactions::create([
                        'reference' => 'R-'. $route_data->route_id,
                        'description' => 'R-' .$route_data->route_id. '-Completed',
                        'amount' => $return_data[0]['final_payout'],
                    ]);

                    JoeyTransactions::create([
                        'transaction_id' => $financialTransaction->id,
                        'joey_id' => $routeHistoryOrder->joey_id,
                        'type' => 'route',
                        'payment_method' => null,
                        'distance' => $joeyRouteLocation->distance,
                        'duration' => '',
                        'date_identifier' => '',
                        'shift_id' => null,
                        'balance' => $return_data[0]['final_payout'],
                    ]);
                }
            }
        }

        if(in_array($postData['status_id'],$this->status_codes['completed']))
        {
            $status = 2;
            if(in_array($postData['creator_id'],$wildForkVendorIds)){
                try{
                    $vendor = Vendor::find($postData['creator_id']);
                    $contact = SprintContact::where('id', $postData['contact_id'])->first();
                    $message = 'Dear '.$contact->name.', Your order # '.$task->merchant_order_num.' from "'.$vendor->name.'" has been delivered. Get delivery details using https://www.joeyco.com/track-order/'.$task->tracking_id.' and also rate our service by clicking on the link https://g.page/r/CaFSrnNcMW1KEB0/review'.'';
                    $receiverNumber = $contact->phone;

                    $account_sid = "ACb414b973404343e8895b05d5be3cc056";
                    $auth_token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                    $twilio_number = "+16479316176";

                    $client = new Client($account_sid, $auth_token);
                    $result = $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message]);

                }catch(\Exception $e){
                }
            }

        }elseif (in_array($postData['status_id'],$this->status_codes['return'])){
            $status = 4;
            if(in_array($postData['creator_id'],$wildForkVendorIds)){
                try{
                    $vendor = Vendor::find($postData['creator_id']);
                    $contact = SprintContact::where('id', $postData['contact_id'])->first();
                    $message = 'Dear '.$contact->name.', Your order # '.$task->merchant_order_num.' from "'.$vendor->name.'" has been returned. Get delivery details using https://www.joeyco.com/track-order/'.$task->tracking_id.'';
                    $receiverNumber = $contact->phone;

                    $account_sid = "ACb414b973404343e8895b05d5be3cc056";
                    $auth_token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                    $twilio_number = "+16479316176";

                    $client = new \Twilio\Rest\Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message]);

                }catch(\Exception $e){
                }
            }
        }
        elseif (in_array($postData['status_id'],$this->status_codes['pickup'])){
            $status = 3;
            if(in_array($postData['creator_id'],$wildForkVendorIds)){
                try{
                    $vendor = Vendor::find($postData['creator_id']);
                    $contact = SprintContact::where('id', $postData['contact_id'])->first();
                    $message = 'Dear '.$contact->name.', Your order # '.$task->merchant_order_num.' from "'.$vendor->name.'" is on the way for delivery. Track your order using https://www.joeyco.com/track-order/'.$task->tracking_id.'';
                    $receiverNumber = $contact->phone;

                    $account_sid = "ACb414b973404343e8895b05d5be3cc056";
                    $auth_token = "c135f0fc91ff9fdd0fcb805a6bdf3108";
                    $twilio_number = "+16479316176";

                    $client = new \Twilio\Rest\Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message]);

                }catch(\Exception $e){
                }
            }
        }


//        $route_data=JoeyRoutes::join('joey_route_locations','joey_route_locations.route_id','=','joey_routes.id')
//            ->where('joey_route_locations.task_id','=',$task->id)
//            ->whereNull('joey_route_locations.deleted_at')
//            ->first(['joey_route_locations.id','joey_routes.joey_id','joey_route_locations.route_id','joey_route_locations.ordinal','joey_route_locations.task_id']);
//
        if(!empty($route_data))
        {
            $routeHistoryRecord = [
                'route_id' =>$route_data->route_id,
                'route_location_id' => $route_data->id,
                'ordinal' => $route_data->ordinal,
                'joey_id'=>  $route_data->joey_id,
                'task_id'=>$task->id,
                'status'=> $status,
                'type'=>'Manual',
                'updated_by'=>auth()->user()->id,
            ];
            RouteHistory::create($routeHistoryRecord);
        }
        $statusDescription= StatusMap::getDescription($postData['status_id']);
        $updateData = [
            'ordinal' => $task->ordinal,
            'task_id' => $task->id,
            'joey_id' =>$route_data->joey_id,
            'name' => $statusDescription,
            'title' => $statusDescription,
            'confirmed' => 1,
            'input_type' => 'image/jpeg',
            'attachment_path' => $attachment_path
        ];
        SprintConfirmation::create($updateData);


        if(!empty($task->id)) {
            $order_id = $task->sprint_id;
            $ctc_vendor_id = CtcVendor::where('vendor_id', '=', $task->creator_id)->first();
            if ($postData['status_id']== 124 && !empty($ctc_vendor_id)) {
                $taskhistory = TaskHistory::where('sprint_id', '=', $order_id)->where('status_id', '=', 125)->first();
                if ($taskhistory == null) {

                    $pickupstoretime_date=new \DateTime();
                    $pickupstoretime_date->modify('-2 minutes');

                    $taskhistory = new TaskHistory();
                    $taskhistory->sprint_id = $order_id;
                    $taskhistory->sprint__tasks_id = $task->id;

                    $taskhistory->status_id = 125;
                    $taskhistory->date = $pickupstoretime_date->format('Y-m-d H:i:s');
                    $taskhistory->created_at = $pickupstoretime_date->format('Y-m-d H:i:s');
                    $taskhistory->save();
                }

            }

            $delivery_status = [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136];

            if (in_array($postData['status_id'], $delivery_status)) {

                $taskhistory = TaskHistory::where('sprint_id', '=', $order_id)->where('status_id', '=', 121)->first();
                if ($taskhistory == null) {

                    $pickuptime_date=new \DateTime();
                    $pickuptime_date->modify('-2 minutes');

                    $taskhistory = new TaskHistory();
                    $taskhistory->sprint_id = $order_id;
                    $taskhistory->sprint__tasks_id = $task->id;
                    $taskhistory->status_id = 121;
                    $taskhistory->date=$pickuptime_date->format('Y-m-d H:i:s');
                    $taskhistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                    $taskhistory->save();

                    if(!empty($route_data)){

                        $routehistory=new RouteHistory();
                        $routehistory->route_id=$route_data->route_id;
                        $routehistory->joey_id=$route_data->joey_id;
                        $routehistory->status=3;
                        $routehistory->route_location_id=$route_data->id;
                        $routehistory->task_id=$route_data->task_id;
                        $routehistory->ordinal=$route_data->ordinal;
                        $routehistory->created_at=$pickuptime_date->format('Y-m-d H:i:s');
                        $routehistory->updated_at=$pickuptime_date->format('Y-m-d H:i:s');
                        $routehistory->type='Manual';
                        $routehistory->updated_by=Auth::guard('web')->user()->id;

                        $routehistory->save();

                    }
                    $this->updateAmazonEntry(121,$order_id);
                    $this->updateBorderLessDashboard(121,$order_id);
                    $this->updateCTCEntry(121,$order_id);
                    $this->updateClaims(121,$order_id);


                }

            }
        }

        Task::where('id','=',$task->id)->update(['status_id'=>$postData['status_id']]);
        Sprint::where('id','=',$task->sprint_id)->whereNull('deleted_at')->update(['status_id'=>$postData['status_id']]);

        $this->updateAmazonEntry($postData['status_id'],$task->sprint_id,$attachment_path);
        $this->updateBorderLessDashboard($postData['status_id'],$task->sprint_id,$attachment_path);
        $this->updateCTCEntry($postData['status_id'],$task->sprint_id,$attachment_path);
        $this->updateClaims($postData['status_id'],$task->sprint_id,$attachment_path);


        $taskHistoryRecord = [
            'sprint__tasks_id' =>$task->id,
            'sprint_id' => $task->sprint_id,
            'status_id' => $postData['status_id'],
            'date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),

        ];
        SprintTaskHistory::create( $taskHistoryRecord );

        $createData = [
            'tracking_id' => $task->tracking_id,
            'status_id' => $postData['status_id'],
            'user_id' => auth()->user()->id,
            'attachment_path' => $attachment_path,
            'reason_id' => $postData['reason_id'],
            'domain' => 'dashboard'
        ];
        TrackingImageHistory::create($createData);

        if (isset($route_data->joey_id)) {
            $deviceIds = UserDevice::where('user_id', $route_data->joey_id)->where('is_deleted_at', 0)->pluck('device_token');
            $subject = 'R-' . $route_data->route_id . '-' . $route_data->ordinal;
            $message = 'Your order status has been changed to ' . $this->statusmap($postData['status_id']);
            Fcm::sendPush($subject, $message, 'ecommerce', null, $deviceIds);
            $payload = ['notification' => ['title' => $subject, 'body' => $message, 'click_action' => 'ecommerce'],
                'data' => ['data_title' => $subject, 'data_body' => $message, 'data_click_action' => 'ecommerce']];
            $createNotification = [
                'user_id' => $route_data->joey_id,
                'user_type' => 'Joey',
                'notification' => $subject,
                'notification_type' => 'ecommerce',
                'notification_data' => json_encode(["body" => $message]),
                'payload' => json_encode($payload),
                'is_silent' => 0,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            UserNotification::create($createNotification);
        }

        session()->flash('alert-success', 'Image Uploaded');
        return Redirect::to('search/orders/trackingid/'.$task->sprint_id.'/details');

    }

    public function sendData($method, $uri, $data=[] ) {
       $host = 'smrtesting.com';

        $json_data = json_encode($data);

        $headers = [
            'Accept-Encoding: utf-8',
            'Accept: application/json; charset=UTF-8',
            'Content-Type: application/json; charset=UTF-8',
            'User-Agent: JoeyCo',
            'Host: ' . $host,
        ];

        if (!empty($json_data)) {

            $headers[] = 'Content-Length: ' . strlen($json_data);
        }

        $url = 'https://smrtesting.com/ksa/assets/public/index.php';

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (strlen($json_data) > 2) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }

        // $file=env('APP_ENV');
        //   dd(env('APP_ENV') === 'local');
        if (env('APP_ENV') === 'local') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        set_time_limit(0);

        $this->originalResponse = curl_exec($ch);

        $error = curl_error($ch);


       // dd([$this->originalResponse,$error,$this->response]);
        curl_close($ch);

        if (empty($error)) {


            $this->response = explode("\n", $this->originalResponse);

            $code = explode(' ', $this->response[0]);
            $code = $code[1];

            $this->response = $this->response[count($this->response) - 1];
            $this->response = json_decode($this->response);

            if (json_last_error() != JSON_ERROR_NONE) {

                $this->response = (object) [
                    'copyright' => 'Copyright © ' . date('Y') . ' JoeyCo Inc. All rights reserved.',
                    'http' => (object) [
                        'code' => 500,
                        'message' => json_last_error_msg(),
                    ],
                    'response' => new \stdClass()
                ];
            }
        }
        else{
                dd(['error'=> $error,'responce'=>$this->originalResponse]);
        }

        return $this->response;
    }

    public function  updateCTCEntry($status_id,$order_id,$imageUrl=null)
    {
        if($status_id==133)
        {
            // Get amazon enteries data from tracking id and check if the data exist in database and if exist update the sort date of the tracking id and status of that tracking id.
            $ctc_entries =CTCEntry::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
            if($ctc_entries!=null)
            {

                $ctc_entries->sorted_at=date('Y-m-d H:i:s');
                $ctc_entries->task_status_id=133;
                $ctc_entries->order_image=$imageUrl;
                $ctc_entries->save();

            }
        }
        elseif($status_id==121)
        {
            $ctc_entries =CTCEntry::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
            if($ctc_entries!=null)
            {
                $ctc_entries->picked_up_at=date('Y-m-d H:i:s');
                $ctc_entries->task_status_id=121;
                $ctc_entries->order_image=$imageUrl;
                $ctc_entries->save();

            }
        }
        elseif(in_array($status_id,[17,113,114,116,117,118,132,138,139,144]))
        {
            $ctc_entries =CTCEntry::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
            if($ctc_entries!=null)
            {
                $ctc_entries->delivered_at=date('Y-m-d H:i:s');
                $ctc_entries->task_status_id=$status_id;
                $ctc_entries->order_image=$imageUrl;
                $ctc_entries->save();

            }
        }
        elseif(in_array($status_id,[104,105,106,107,108,109,110,111,112,131,135,136,101,102,103,140,143]))
        {
            $ctc_entries =CTCEntry::where('sprint_id','=',$order_id)->whereNull('deleted_at')->first();
            if($ctc_entries!=null)
            {
                $ctc_entries->returned_at=date('Y-m-d H:i:s');
                $ctc_entries->task_status_id=$status_id;
                $ctc_entries->order_image=$imageUrl;
                $ctc_entries->save();

            }
        }

    }

    public function SearchTracking(Request $request)
    {
        // date_default_timezone_set("America/Toronto");

        $tracking_ids=trim($request->input('tracking_id'));
        $return=[];
        $return['is_pickedup']=0;
        $return['is_delivered_return']=0;
        $return['is_delivered']=0;
        $return['is_returned']=0;

        if(!empty($tracking_ids))
        {
            $return_status = [101,102,103,104,105,106,107,108,109,110,111,112,131,135,136,137,140];
            $delivered_status = [17,113,114,116,117,118,132,138,139,144];
            // $delay_status = [103,102,137,140];
           $delivered_and_return_status = [17,113,114,116,117,118,132,138,139,144,101,103,104,105,106,107,108,109,110,111,112,131,135,136,140];
           $merchantid=MerchantIds::where('tracking_id',$tracking_ids)->first();
            if(!empty($merchantid)){
                $return=$this->SearchTrackingDetails($merchantid->task->sprint_id,$merchantid,$request);
            
                $return['is_pickedup']=0;
                $return['is_delivered_return']=0;
                $return['is_delivered']=0;
                $return['is_returned']=0;

                $task_histories=$merchantid->Task->sprintTaskHistoryDetail;
                foreach ($task_histories as $task_history) {
                    if($task_history->status_id==121){ //picked up
                        $return['is_pickedup']=1;
                    }
                    if(in_array($task_history->status_id,$delivered_and_return_status)){ //delivered or return
                        $return['is_delivered_return']=1;
                        $return['msg_deliver_return']=$this->statusmap($task_history->status_id)." at ".ConvertTimeZone($task_history->created_at,$CurrentTimeZone = 'UTC' ,$ConvertTimeZone = 'America/Toronto',$format = 'd M Y h:i a')??"";
                        // date('d M Y h:i a', strtotime($task_history->created_at))??"";
                        if(in_array($task_history->status_id,$delivered_status)){
                            $return['is_delivered']=1;
                        }
                        elseif(in_array($task_history->status_id,$return_status)){
                            $return['is_returned']=1;
                        }
                    }
                }
                // echo $return['is_pickedup'].'  '.$return['is_delivered_return'];die;
            }
        }
        return backend_view('search-trackin-details', $return);
    }

    public function SearchTrackingDetails($sprintId,$merchantid,$request)
    {

        $show_message = $request->message;
        if(!is_null($show_message))
        {
            $current_url  = $request->url();
            $query_string = http_build_query( $request->except(['message'] ) );
            return redirect($current_url.'?'.$query_string)
                ->with('alert-success', $show_message);
        }
  
        $result= Sprint::join('sprint__tasks','sprint_id','=','sprint__sprints.id')
            ->leftJoin('merchantids','merchantids.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            ->leftJoin('joey_routes','joey_routes.id','=','joey_route_locations.route_id')
            ->leftJoin('joeys','joeys.id','=','joey_routes.joey_id')
            ->join('locations','sprint__tasks.location_id','=','locations.id')
            ->join('sprint__contacts','contact_id','=','sprint__contacts.id')
            ->leftJoin('vendors','creator_id','=','vendors.id')
            ->where('sprint__tasks.sprint_id','=',$sprintId)
            ->whereNull('joey_route_locations.deleted_at')
            ->orderBy('ordinal','DESC')->take(1)
            ->get(array('sprint__tasks.*','joey_routes.id as route_id','locations.address','locations.suite','locations.postal_code','sprint__contacts.name','sprint__contacts.phone','sprint__contacts.email',
                'joeys.first_name as joey_firstname','joeys.id as joey_id',
                'joeys.last_name as joey_lastname','vendors.id as merchant_id','vendors.first_name as merchant_firstname','vendors.last_name as merchant_lastname','merchantids.scheduled_duetime'
            ,'joeys.id as joey_id','merchantids.tracking_id','joeys.phone as joey_contact','joey_route_locations.ordinal as stop_number','merchantids.merchant_order_num','merchantids.address_line2','sprint__sprints.creator_id','sprint__sprints.is_hub'));
  
        $i=0;
  
        $data = [];
        $sprint_id = 0;
        // $order_type = ($result[0]->is_hub > 0)?'ecommerce':'grocery';
        foreach($result as $tasks){
            $sprint_id = $tasks->sprint_id;
            $status2 = array();
            $status = array();
            $status1 = array();
           
            $tasks->joey_address='';
            $tasks->expected_datetime='';
            $tasks->joey_lat=0;
            $tasks->joey_lng=0;
            $tasks->duration=0;

            // Joey address
            $joeyLocation=JoeyLocation::where('joey_id',$tasks->joey_id)->orderBy('id',"DESC")->first();
            // print_r($joeyLocation);die;
            if(!empty($joeyLocation)){
                $tasks->joey_lat=(float)(((int)(substr($joeyLocation->latitude,0,8)))/1000000);
                $tasks->joey_lng=(float)(((int)(substr($joeyLocation->longitude,0,9)))/1000000);
                // $tasks->joey_address=$this->getAddressByLatLng($tasks->joey_lat,$tasks->joey_lng);
                $getAddressByLatLng=$this->getAddressByLatLng($tasks->joey_lat,$tasks->joey_lng);
                if( $getAddressByLatLng['status']==200){
                    $tasks->joey_address=$getAddressByLatLng['address'];
                }else{
                    // $tasks->joey_address='Invalid joey location';
                    $tasks->joey_address=0;
                }
                // echo $tasks->joey_address;die;
            }
            // Joey address

            // Expected Arrival

            $expected_date='';
            $response['is_amazon']=0;
            // echo $merchantid->sprintTaskDetail->id;die;
            $vendor_check=$merchantid->Task->sprint->creator_id;
            // dd($merchantid->Task->sprintTaskHistoryDetail);
            if($vendor_check==477260 || $vendor_check==477282  || $vendor_check==476592){

                // echo 1;die;
                // $response['is_amazon']=1;
                // $expected_date=date('Y-m-d H:i:s', strtotime(ConvertTimeZone($merchantid->created_at->toDateTimeString(),"UTC",'America/Toronto'). ' +1 day'));
                $amazon_sprint_task_histories=$merchantid->Task->sprintTaskHistoryDetail;
                if(!empty($amazon_sprint_task_histories)){
                    foreach ($amazon_sprint_task_histories as $amazon_sprint_task_history) {
                        if($amazon_sprint_task_history->status_id==13){
                            $expected_date=date('Y-m-d H:i:s', strtotime(ConvertTimeZone($amazon_sprint_task_history->created_at->toDateTimeString(),"UTC",'America/Toronto'). ' +1 day'));
                        }
                    }
                    if($expected_date==''){
                        foreach ($amazon_sprint_task_histories as $amazon_sprint_task_history) {
                            if($amazon_sprint_task_history->status_id==61){
                                $expected_date=date('Y-m-d H:i:s', strtotime(ConvertTimeZone($amazon_sprint_task_history->created_at->toDateTimeString(),"UTC",'America/Toronto'). ' +1 day'));

                            }
                        }
                    }
                }


            }else{
                // echo 2;die;

                // $expected_date=date('Y-m-d H:i:s', strtotime(ConvertTimeZone($merchantid->created_at->toDateTimeString(),"UTC",'America/Toronto'). ' +1 day'));
                $other_sprint_task_histories=$merchantid->Task->sprintTaskHistoryDetail;
                if(!empty($other_sprint_task_histories)){
                    foreach ($other_sprint_task_histories as $other_sprint_task_history) {
                        if($other_sprint_task_history->status_id==125){
                            $expected_date=date('Y-m-d H:i:s', strtotime(ConvertTimeZone($other_sprint_task_history->created_at->toDateTimeString(),"UTC",'America/Toronto'). ' +1 day'));
                        }
                    }
                    if($expected_date==''){
                        foreach ($other_sprint_task_histories as $other_sprint_task_history) {
                            if($other_sprint_task_history->status_id==133){
                                $expected_date=date('Y-m-d H:i:s', strtotime(ConvertTimeZone($other_sprint_task_history->created_at->toDateTimeString(),"UTC",'America/Toronto'). ' +1 day'));

                            }
                        }
                    }

                }


            }

            if($expected_date!='' || $expected_date!=null){
                $tasks->expected_datetime=date("Y-m-d",strtotime($expected_date))." 21:00:00";
            }

            // Expected Arrival

            // Duration
                // echo $merchantid->Task->sprint->Vendor->id;die;
                // $tasks->vendoraddress= $merchantid->Task->sprint->Vendor->location->address??null;
                // $from['name']=$merchantid->Task->sprint->Vendor->location->address;
                // $from['lat']=(float)(((int)(substr($merchantid->Task->sprint->Vendor->location->latitude,0,8)))/1000000);
                // $from['lng']=(float)(((int)(substr($merchantid->Task->sprint->Vendor->location->longitude,0,9)))/1000000);

                $from['name']=$tasks->joey_address;
                $from['lat']=$tasks->joey_lat;
                $from['lng']=$tasks->joey_lng;
                

                $to['name']=$merchantid->Task->task_Location->address;
                $to['lat']=(float)(((int)(substr($merchantid->Task->task_Location->latitude,0,8)))/1000000);
                $to['lng']=(float)(((int)(substr($merchantid->Task->task_Location->longitude,0,9)))/1000000);

                $tasks->cust_lat=$to['lat'];
                $tasks->cust_lng=$to['lng'];

            //    print_r($from);die;
                $duration=$this->gettimedifference($from,$to);
                // print_r($duration);die;
                if(!isset($duration['status'])){
                    $tasks->duration=$duration;
                }


            // Duration

            // dd($tasks);
            $data[$i] =  $tasks;
            $taskHistory= TaskHistory::where('sprint_id','=',$tasks->sprint_id)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                //->where('active','=',1)
                ->get(['status_id',\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);
  
            $returnTOHubDate = SprintReattempt::
            where('sprint_reattempts.sprint_id','=' ,$tasks->sprint_id)->orderBy('created_at')
                ->first();
  
            if(!empty($returnTOHubDate))
            {
                $taskHistoryre= TaskHistory::where('sprint_id','=', $returnTOHubDate->reattempt_of)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                    //->where('active','=',1)
                    ->get(['status_id',\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);
  
                foreach ($taskHistoryre as $history){
  
                    $status[$history->status_id]['id'] = $history->status_id;
                    if($history->status_id==13)
                    {
                        $status[$history->status_id]['description'] ='At hub - processing';
                    }
                    else
                    {
                        $status[$history->status_id]['description'] =$this->statusmap($history->status_id);
                    }
                    $status[$history->status_id]['created_at'] = $history->created_at;
  
                }
  
            }
            if(!empty($returnTOHubDate))
            {
                $returnTO2 = SprintReattempt::
                where('sprint_reattempts.sprint_id','=' , $returnTOHubDate->reattempt_of)->orderBy('created_at')
                    ->first();
  
                if(!empty($returnTO2))
                {
                    $taskHistoryre= TaskHistory::where('sprint_id','=',$returnTO2->reattempt_of)->WhereNotIn('status_id',[17,38,0])->orderBy('date')
                        //->where('active','=',1)
                        ->get(['status_id',\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at")]);
  
                    foreach ($taskHistoryre as $history){
  
                        $status2[$history->status_id]['id'] = $history->status_id;
                        if($history->status_id==13)
                        {
                            $status2[$history->status_id]['description'] ='At hub - processing';
                        }
                        else
                        {
                            $status2[$history->status_id]['description'] = $this->statusmap($history->status_id);
                        }
                        $status2[$history->status_id]['created_at'] = $history->created_at;
  
                    }
  
                }
            }
  
            //    dd($taskHistory);
  
            foreach ($taskHistory as $history){
  
                $status1[$history->status_id]['id'] = $history->status_id;
  
                if($history->status_id==13)
                {
                    $status1[$history->status_id]['description'] ='At hub - processing';
                }
                else
                {
                    $status1[$history->status_id]['description'] = $this->statusmap($history->status_id);
                }
                $status1[$history->status_id]['created_at'] = $history->created_at;
  
            }
  
            if($status!=null)
            {
                $sort_key = array_column($status, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status);
            }
            if($status1!=null)
            {
                $sort_key = array_column($status1, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status1);
            }
            if($status2!=null)
            {
                $sort_key = array_column($status2, 'created_at');
                array_multisort($sort_key, SORT_ASC, $status2);
            }
  
  
            $data[$i]['status']= $status;
            $data[$i]['status1']= $status1;
            $data[$i]['status2']=$status2;
            $i++;
        }
  
        //   $reasons = Reason::all();
  
        //getting category ref id of dashboard portal
        /*$portal =  FlagCategoryMetaData::where('type', 'vendor_relation')
            ->where('value', $vendor_id)
            ->pluck('category_ref_id');*/
        //getting flag categories
        /*$flagCategories =  CustomerFlagCategories::whereIn('id', $portal)
            ->where('parent_id', 0)
            ->where('is_enable', 1)
            ->get();*/
        //getting flag categories
        // $flagCategories =  CustomerFlagCategories::where('parent_id', 0)
        //     ->where('is_enable', 1)
        //     ->whereNull('deleted_at')
        //     ->get();
        // $CategoryId = $flagCategories->pluck('id');
        // $flagSubCategories =  CustomerFlagCategories::whereIn('parent_id', $CategoryId)
        //     ->get();
  
        //getting joey performance flag
        // $joey_flags_history = FlagHistory::where('sprint_id',$sprint_id)
        //     ->orderBy('id', 'DESC')
        //     ->where('unflaged_by','=',0)
        //     ->get();
        // dd($data);
        //   return backend_view('search-trackin-details',
        //       [
        //           'data'=>$data,
        //           'sprintId' => $sprintId,
        //           'reasons' => $reasons,
        //           'flagCategories' => $flagCategories,
        //           'flagSubCategories' => $flagSubCategories,
        //           'joey_flags_history' => $joey_flags_history,
        //           'order_type' => $order_type
        //       ]
        //   );
       
        $return=[
            'data'=>$data,
            'sprintId' => $sprintId,
            // 'reasons' => $reasons,
            // 'flagCategories' => $flagCategories,
            // 'flagSubCategories' => $flagSubCategories,
            // 'joey_flags_history' => $joey_flags_history,
            // 'order_type' => $order_type
        ];
        return $return;
    }
    public function gettimedifference($from=[],$to=[])
    {
        $ch = curl_init();

          $data=array(
            "visits"=>[
                "order_1"=>[
                   "location"=>[
                       "name"=>$to['name'],
                       "lat"=>$to['lat'],
                       "lng"=>$to['lng']
                   ]
                ]
            ],
            "fleet"=>[
                "vehicle_1"=>[
                   "start_location"=>[
                       "id" => "depot",
                       "name"=>$from['name'],
                       "lat"=>$from['lat'],
                       "lng"=>$from['lng']
                   ]
                ]
            ],
         );
        // $data=array(
        //     "visits"=>[
        //         "order_1"=>[
        //            "location"=>[
        //                "name"=>'Maingate Dr & Eglinton Ave E, Mississauga, ON L4W 1N5, Canada',
        //                "lat"=>43.631064,
        //                "lng"=>-79.627266
        //            ]
        //         ]
        //     ],
        //     "fleet"=>[
        //         "vehicle_1"=>[
        //            "start_location"=>[
        //                "id" => "depot",
        //                "name"=>'Bharat Sevashram Sangha Canada, 2107 Codlin Crescent, Etobicoke, ON M9W 5K7, Canada',
        //                "lat"=>43.749976,
        //                "lng"=>-79.635163
        //            ]
        //         ]
        //     ],
        //  );
          $data = json_encode($data);
        // print_r($data);
        // die();

        curl_setopt($ch, CURLOPT_URL,"https://api.routific.com/v1/vrp");
        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS,
        //          http_build_query(array('postvar1' => 'value1')));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJfaWQiOiI1Njk5ZDJjODUzNWFkMTBkMWQ0YmFlMTgiLCJpYXQiOjE0NTgxNjgzNjR9.RXZHpu7tVE3dersb5TZrtJMM8u4BehM0PriS9Dj1YAc'
        ));
        // routific_api_key
        // Receive server response ...
        $server_output = curl_exec($ch);
        curl_close ($ch);
        $res =json_decode($server_output,true);
        // print_r($res);die;
        // $res=
        // if($res['status']=="success"){
        //     echo $res['total_travel_time'];die;
        // }
        // die;
        if(isset($res['total_travel_time'])){
            // return $res['total_travel_time'];
            $return_data=$res['total_travel_time'];
        }
        else{
            $return_data['status']=400;
            $return_data['error']=$res['error'];
            $return_data['error_type']=$res['error_type'];
            // return $return_data;
        }
        return $return_data;

    }
    public function getAddressByLatLng($lat,$lng)
    {
        $latlng=$lat.','.$lng;
        // $latlng = urlencode($latlng);
        $return=[];

        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0";
        if (($resp_json = @file_get_contents($url)) === false) {
            // $error = error_get_last();
            // echo "HTTP request failed. Error was: " . $error['message'];die;
            $return['status']=400;
        }
        
        // echo $url;die;
        // get the json response
        // $resp_json = file_get_contents($url);

        // decode the json
        else{
            $resp = json_decode($resp_json, true);

            // response status will be 'OK', if able to geocode given address
            if($resp['status']=='OK'){

                $completeAddress = [];
                $addressComponent = $resp['results'][0]['address_components'];

                // get the important data

                for ($i=0; $i < sizeof($addressComponent); $i++) {
                if ($addressComponent[$i]['types'][0] == 'administrative_area_level_1')
                {
                $completeAddress['division'] = $addressComponent[$i]['short_name'];
                }
                elseif ($addressComponent[$i]['types'][0] == 'locality') {
                $completeAddress['city'] = $addressComponent[$i]['short_name'];
                }
                else {
                $completeAddress[$addressComponent[$i]['types'][0]] = $addressComponent[$i]['short_name'];
                }
                if($addressComponent[$i]['types'][0] == 'postal_code'){
                $completeAddress['postal_code'] = $addressComponent[$i]['short_name'];
                }
                }

                if (array_key_exists('subpremise', $completeAddress)) {
                $completeAddress['suite'] = $completeAddress['subpremise'];
                unset($completeAddress['subpremise']);
                }
                else {
                $completeAddress['suite'] = '';
                }


                $completeAddress['address'] = $resp['results'][0]['formatted_address'];

                $completeAddress['lat'] = $resp['results'][0]['geometry']['location']['lat'];
                $completeAddress['lng'] = $resp['results'][0]['geometry']['location']['lng'];
                $completeAddress['status']=200;
                unset($completeAddress['administrative_area_level_2']);

                $return['status']=200;
                $return['address']= $completeAddress['address'];
            }

        }
        // else{
        // throw new GenericException($resp['status'],403);
        // return 0;
        // return $error['status']=0;
        // }
        return $return;


    }
    public function updateClaims($sprint_status_id,$sprint_id,$imageUrl=null)
    {
        $updateData = [
            'sprint_status_id'=>$sprint_status_id,
            ];
        if ($imageUrl != null)
        {
            $updateData['image'] = $imageUrl;
        }
        Claim::where('sprint_id',$sprint_id)->update($updateData);
    }


}
