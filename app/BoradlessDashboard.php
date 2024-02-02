<?php

namespace App;

use App\Http\Traits\BasicModelFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BoradlessDashboard extends Model
{
    use BasicModelFunctions, SoftDeletes;

    protected $table = 'boradless_dashboard';
   
    protected $fillable = [
        'id' , 
        'sprint_id' , 
        'task_id' , 
        'creator_id' , 
        'route_id' , 
        'ordinal' , 
        'tracking_id' , 
        'joey_id' ,
        'eta_time',
        'store_name',
        'customer_name',
        'weight' ,
        'joey_name' ,
        'picked_up_at' , 
        'sorted_at' , 
        'delivered_at' , 
        'returned_at' , 
        'hub_return_scan' , 
        'task_status_id' , 
        'order_image' , 
        'address_line_1' , 
        'address_line_2' , 
        'address_line_3' , 
        'created_at' , 
        'updated_at' , 
        'deleted_at' , 
        'is_custom_route'
    ];

    public function sprintBoradlessTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'sprint_id')->where('type','dropoff')->orderby('id','DESC')->select('id','status_id','ordinal','location_id','contact_id',\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') as eta_time"));
    }
    public function sprintScarboroughTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'sprint_id')->where('type','dropoff')->orderby('id','DESC')
            ->select('id','status_id','ordinal','location_id','contact_id',\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') as eta_time"));
    }
    public function SprintTaskHistory()
    {
        return $this->hasMany( SprintTaskHistory::class,'sprint_id', 'sprint_id');
    }
    public function pickupFromStore()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id=125 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as pickup")))
            ->where('status_id',125)->orderBy('date','ASC')->limit(2)->first();
    }
    public function atHubProcessing()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (133) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->first();
    }

    public function deliveryTime()
    {
        return $this->SprintTaskHistory()
            ->select((DB::raw('MAX(CASE WHEN status_id IN(17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();
    }

    public function sprintReattempts()
    {
        return $this->belongsTo(SprintReattempt::class,'sprint_id','sprint_id');
    }
    public function pickupFromStoreOtd($boradless)
    {
        foreach($boradless->SprintTaskHistory as $BoradTask)
        {
            if($BoradTask->status_id == 125)
            {
                $PickupDate = $BoradTask->created_at;
                $America_Toronto_time = Carbon::parse($PickupDate)->timezone('America/Toronto');    //getTimezone = get time zone, Timezone    = set time zone     
                return $America_Toronto_time->format('Y-m-d');
                break;
            }
        }

        // $date = $this->SprintTaskHistory()->where('status_id', 125)->value('created_at');
        // $America_Toronto_time = Carbon::parse($date)->timezone('America/Toronto');    //getTimezone = get time zone, Timezone    = set time zone     
        // return $America_Toronto_time->format('Y-m-d');

        // created_at timestamp from UTC to the 'America/Toronto' timezone.
        // gating current routs tasks ids
        // return $this->SprintTaskHistory()  // get all order tasks of on the base of sprint_id
        //                 ->select((DB::raw("MAX(CASE WHEN status_id=125 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as pickup")))
        //                 ->where('status_id',125)
        //                 ->orderBy('date','ASC')
        //                 ->limit(2)
        //                 ->first();
    }
    public function atHubProcessingOtd($boradless)
    {
        foreach($boradless->SprintTaskHistory as $BoradTask)
        {
            if($BoradTask->status_id == 124)
            {
                $PickupDate = $BoradTask->created_at;
                $America_Toronto_time = Carbon::parse($PickupDate)->timezone('America/Toronto');    //getTimezone = get time zone, Timezone    = set time zone     
                return $America_Toronto_time->format('Y-m-d');
                break;
            }
        }

        // gating current routs tasks ids
        // return $this->SprintTaskHistory();
                    // ->select((DB::raw("MAX(CASE WHEN status_id IN (124) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))
                    // ->where('status_id',124)
                    // ->orderBy('date','ASC')
                    // ->limit(2)
                    // ->first();
    }
    public function outForDelivery($boradless)
    {
        // dd($boradless->SprintTaskHistory[0]);

        foreach($boradless->SprintTaskHistory as $BoradTask)
        {
            if($BoradTask->status_id == 121)
            {
                $PickupDate = $BoradTask->created_at;
                $America_Toronto_time = Carbon::parse($PickupDate)->timezone('America/Toronto');    //getTimezone = get time zone, Timezone    = set time zone     
                return $America_Toronto_time->format('Y-m-d');
                break;
            }
        }

        // gating current routs tasks ids
        // return $this->SprintTaskHistory()
        //     ->select((DB::raw("MAX(CASE WHEN status_id=121 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as outdeliver")))->first();
    }
    public function deliveryTimeOTD($boradless)
    {
        $allowedStatusIds = [17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143];

        foreach($boradless->SprintTaskHistory as $BoradTask)
        {
            if (in_array($BoradTask->status_id, $allowedStatusIds)) 
            {   
                $PickupDate = $BoradTask->created_at;
                $America_Toronto_time = Carbon::parse($PickupDate)->timezone('America/Toronto');    //getTimezone = get time zone, Timezone    = set time zone     
                return $America_Toronto_time->format('Y-m-d');
                break;
            }
        }

        // gating current routs tasks ids
        // return $this->SprintTaskHistory()
        //     ->select((DB::raw('MAX(CASE WHEN status_id IN(17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();
    }
    public function actualDeliveryTime($boradless)
    {
        $allowedStatusIds = [17, 113, 114, 116, 117, 118, 132, 138, 139, 144];

        foreach($boradless->SprintTaskHistory as $BoradTask)
        {
            if (in_array($BoradTask->status_id, $allowedStatusIds)) 
            {   
                $PickupDate = $BoradTask->created_at;
                $America_Toronto_time = Carbon::parse($PickupDate)->timezone('America/Toronto');    //getTimezone = get time zone, Timezone    = set time zone     
                $data['actual_delivery'] = $America_Toronto_time->format('Y-m-d');
                $data['status_id'] =  $BoradTask->status_id;

                return $data;
                break;
            }
        }
        // return $this->SprintTaskHistory()
        //     ->select((DB::raw('MAX(CASE WHEN status_id IN (17, 113, 114, 116, 117, 118, 132, 138, 139, 144) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as actual_delivery')),
        //         (DB::raw('MAX(CASE WHEN status_id IN ( 113, 114, 116, 117, 118, 132, 138, 139, 144) THEN status_id ELSE NULL END) as status_id')))
        //         ->first();

    }
    public function sprintReattemptsOTD()
    {
        return $this->belongsTo(SprintReattempt::class,'sprint_id','reattempt_of');
    }
    public function atHubProcessingFirst()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (124) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->where('status_id',124)->orderBy('date','ASC')->limit(2)->first();
    }
    public function boradlessAtHubProcessingFirst()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (124) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->where('status_id',124)->orderBy('date','ASC')->limit(2)->first();
    }
    public function getInprogressOrders($taskIds, $type)
    {
        $totalRecord = DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id',[133,121])
            ->get(['route_id','task_status_id']);
        $total = 0;
        $remaining_sorted = 0;
        $remaining_pickup = 0;
        $remaining_route = [];
        $routes = [];
        foreach ($totalRecord as $record)
        {
            if ($record->task_status_id == 133){
                $remaining_sorted = $remaining_sorted + 1 ;
            }
            if ($record->task_status_id == 121){
                $remaining_pickup = $remaining_pickup + 1 ;
            }
            if ($record->task_status_id == 121 ){
                $routes[] = $record->route_id;
            }
        }

        $counts['remaining_sorted'] = $remaining_sorted;
        $counts['remaining_pickup'] = $remaining_pickup;
        $counts['remaining_route'] = count(array_unique($routes));
        return $counts;
    }
    public function getSprintCounts($sprintIds){

        $counts['total'] = $this->totalOrders($sprintIds);
        $counts['picked-up'] = $this->picked_up($sprintIds);
        $counts['at-hub'] = $this->at_hub($sprintIds);
        $counts['at-store'] = $this->at_store($sprintIds);
        $counts['sorted-order'] = $this->sorted_order($sprintIds);
        $counts['out-for-delivery'] = $this->out_for_delivery($sprintIds);
        $counts['delivered-order'] = $this->delivery_order($sprintIds);
        $counts['returned'] = $this->returned($sprintIds);
        $counts['returned-to-merchant'] = $this->returned_to_merchant($sprintIds);
        return $counts;
    }
    public function totalOrders($sprintIds)
    {
        $totalOrders = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->pluck('id');
        return count($totalOrders);
    }
    public function picked_up($sprintIds)
    {
        $picked_up = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->where('task_status_id',125)->pluck('id');
        return count($picked_up);
    }

    public function at_hub($sprintIds)
    {
        $at_hub = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->whereIn('task_status_id',[124,13,120])->pluck('id');
        return count($at_hub);
    }

    public function at_store($sprintIds)
    {
        $at_store = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->where('task_status_id',61)->pluck('id');
        return count($at_store);
    }

    public function sorted_order($sprintIds)
    {
        $sorted_order = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->where('task_status_id',133)->pluck('id');
        return count($sorted_order);
    }

    public function out_for_delivery($sprintIds)
    {
        $out_for_delivery = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->where('task_status_id',121)->pluck('id');
        return count($out_for_delivery);
    }

    public function delivery_order($sprintIds)
    {
        $delivery_order = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->whereIn('task_status_id',$this->getStatusCodes('competed'))->pluck('id');
        return count($delivery_order);
    }

    public function returned($sprintIds)
    {
        $returned = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->whereIn('task_status_id',$this->getStatusCodes('return'))
            ->where('task_status_id','!=',111)->pluck('id');
        return count($returned);
    }
    public function returned_to_merchant($sprintIds)
    {
        $returned_to_merchant = DB::table('boradless_dashboard')->whereIn('id',$sprintIds)->where('task_status_id',111)->pluck('id');
        return count($returned_to_merchant);
    }
    public function getWalmartECommerceCounts($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
            $counts['total'] = $this->WalmartECommercetotalOrders($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->WalmartECommercesorted($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->WalmartECommercepickup($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->WalmartECommercedelivery_order($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->WalmartECommercereturn_orders($taskIds);
            $counts['hub_return_scan'] = $this->WalmartECommercehub_return_scan($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->WalmartECommercenotscan($taskIds);
            $counts['reattempted'] = $this->WalmartECommercereattempted($taskIds);
        }

        if (in_array($type, ['all', 'scan'])){
            if ($this->WalmartECommercepickup($taskIds) > 0 ){
                $counts['completion_ratio'] = round(($this->WalmartECommercedelivery_order($taskIds)/$this->WalmartECommercepickup($taskIds))*100,2);
            }
        }
        return $counts;
    }
    public function WalmartECommercetotalOrders($taskIds)
    {
        $total = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }
    public function WalmartECommercesorted($taskIds)
    {
        $sorted = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->where(['task_status_id' => 133])->whereNotNull('sorted_at')->pluck('task_id');
        return count($sorted);
    }
    public function WalmartECommercepickup($taskIds)
    {
        $pickup = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->where(['task_status_id' => 121])->whereNotNull('picked_up_at')->pluck('task_id');
        return count($pickup);
    }
    public function WalmartECommercedelivery_order($taskIds)
    {
        return $delivery_order = count(DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('competed'))->pluck('task_id'));
    }
    public function WalmartECommercereturn_orders($taskIds)
    {
        return $return_orders = count(DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->pluck('task_id'));
    }
    public function WalmartECommercehub_return_scan($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->where('is_custom_route', 0)->pluck('task_id'));;
    }
    public function WalmartECommercenotscan($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [61, 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    public function WalmartECommercereattempted($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [ 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    /*Wildfork Counts*/
    public function getWildforkECommerceCounts($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
            $counts['total'] = $this->WildforkECommercetotalOrders($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->WildforkECommercesorted($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->WildforkECommercepickup($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->WildforkECommercedelivery_order($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->WildforkECommercereturn_orders($taskIds);
            $counts['hub_return_scan'] = $this->WildforkECommercehub_return_scan($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->WildforkECommercenotscan($taskIds);
            $counts['reattempted'] = $this->WildforkECommercereattempted($taskIds);
        }

        if (in_array($type, ['all', 'scan'])){
            if ($this->WildforkECommercepickup($taskIds) > 0 ){
                $counts['completion_ratio'] = round(($this->WildforkECommercedelivery_order($taskIds)/$this->WildforkECommercepickup($taskIds))*100,2);
            }
        }
        return $counts;
    }
    public function WildforkECommercetotalOrders($taskIds)
    {
        $total = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }
    public function WildforkECommercesorted($taskIds)
    {
        $sorted = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('creator_id',[477625,477633,477635])->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->where(['task_status_id' => 133])->whereNotNull('sorted_at')->pluck('task_id');
        return count($sorted);
    }
    public function WildforkECommercepickup($taskIds)
    {
        $pickup = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereNotNull('picked_up_at')->whereIn('creator_id',[477625,477633,477635])->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
           ->whereNotNull('picked_up_at')->pluck('task_id');
        return count($pickup);
    }
    public function WildforkECommercedelivery_order($taskIds)
    {
        return $delivery_order = count(DB::table('boradless_dashboard')->whereIn('creator_id',[477625,477633,477635])->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('competed'))->pluck('task_id'));
    }
    public function WildforkECommercereturn_orders($taskIds)
    {
        return $return_orders = count(DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])->whereIn('task_status_id', $this->getStatusCodes('return'))->pluck('task_id'));
    }
    public function WildforkECommercehub_return_scan($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->where('is_custom_route', 0)->pluck('task_id'));;
    }
    public function WildforkECommercenotscan($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('creator_id',[477625,477633,477635])->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [61, 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    public function WildforkECommercereattempted($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [ 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    /*end*/
    /*Logx Counts*/
    public function getLogxECommerceCounts($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
            $counts['total'] = $this->logxECommercetotalOrders($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->logxECommercesorted($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->logxECommercepickup($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->logxECommercedelivery_order($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->logxECommercereturn_orders($taskIds);
            $counts['hub_return_scan'] = $this->logxECommercehub_return_scan($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->logxECommercenotscan($taskIds);
            $counts['reattempted'] = $this->logxECommercereattempted($taskIds);
        }

        if (in_array($type, ['all', 'scan'])){
            if ($this->logxECommercepickup($taskIds) > 0 ){
                $counts['completion_ratio'] = round(($this->logxECommercedelivery_order($taskIds)/$this->logxECommercepickup($taskIds))*100,2);
            }
        }
        return $counts;
    }
    public function logxECommercetotalOrders($taskIds)
    {
        $total = DB::table('boradless_dashboard')->where('is_custom_route', 0)->where('creator_id',[477661])->whereNotIn('task_status_id', [38, 36])->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }
    public function logxECommercesorted($taskIds)
    {
        $sorted = DB::table('boradless_dashboard')->where('is_custom_route', 0)->where('creator_id',[477661])->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->where(['task_status_id' => 133])->whereNotNull('sorted_at')->pluck('task_id');
        return count($sorted);
    }
    public function logxECommercepickup($taskIds)
    {
        $pickup = DB::table('boradless_dashboard')->where('is_custom_route', 0)->where('creator_id',[477661])->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->whereNotNull('picked_up_at')->whereNotNull('picked_up_at')->pluck('task_id');
        return count($pickup);
    }
    public function logxECommercedelivery_order($taskIds)
    {
        return $delivery_order = count(DB::table('boradless_dashboard')->where('creator_id',[477661])->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('competed'))->pluck('task_id'));
    }
    public function logxECommercereturn_orders($taskIds)
    {
        return $return_orders = count(DB::table('boradless_dashboard')->where('creator_id',[477661])->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->pluck('task_id'));
    }
    public function logxECommercehub_return_scan($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')->where('creator_id',[477661])->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->where('is_custom_route', 0)->pluck('task_id'));;
    }
    public function logxECommercenotscan($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->where('creator_id',[477661])->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36])
            ->whereIn('task_status_id', [61, 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    public function logxECommercereattempted($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->where('creator_id',[477661])->whereIn('task_id', $taskIds)->whereIn('task_status_id', [ 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    //reattempted orders
    public function boradlesshub_reattempted_orders($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')->whereNull('hub_return_scan')->whereIn('boradless_dashboard.task_id', $taskIds)->whereIn('task_status_id', [155,112])->groupBy('boradless_dashboard.tracking_id')->pluck('boradless_dashboard.task_id'));
    }
    //returned to hub for re delivery orders
    public function boradlessReturnedToHubForReDeliveryOrders($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')->whereNull('hub_return_scan')->whereIn('boradless_dashboard.task_id', $taskIds)->whereIn('task_status_id', [110])->groupBy('boradless_dashboard.tracking_id')->pluck('boradless_dashboard.task_id'));
    }
    /*end*/
    public function getBoradlessCounts($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
            $counts['total'] = $this->boradlesstotalOrders($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->boradlesssorted($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->boradlesspickup($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->boradlessdelivery_order($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->boradlessreturn_orders($taskIds);
            $counts['hub_return_scan'] = $this->boradlesshub_return_scan($taskIds);
            $counts['hub_return_not_scan'] = $this->boradlesshub_return_not_scan($taskIds);
            $counts['reattempted_orders'] = $this->boradlesshub_reattempted_orders($taskIds);
            $counts['re-delivery_orders'] = $this->boradlessReturnedToHubForReDeliveryOrders($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->boradlessnotscan($taskIds);
            $counts['reattempted'] = $this->boradlessreattempted($taskIds);
        }

        if (in_array($type, ['all', 'scan'])){
            if ($this->boradlesspickup($taskIds) > 0 ){
                $counts['completion_ratio'] = round(($this->boradlessdelivery_order($taskIds)/$this->boradlesspickup($taskIds))*100,2);
            }
        }
        return $counts;
    }
    public function boradlesstotalOrders($taskIds)
    {
        $total = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }
    public function boradlesssorted($taskIds)
    {
        /*$sorted = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36, 0])->where(['task_status_id' => 133])->whereIn('task_id', $taskIds)->whereNotNull('sorted_at')->pluck('task_id');*/
        $sorted = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36, 0])->where(['task_status_id' => 133])->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($sorted);
    }
    public function boradlesspickup($taskIds)
    {
        //$pickup = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereNotNull('picked_up_at')->pluck('task_id');
        $pickup = DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereNotIn('task_status_id', [38, 36, 0])
            ->where(['task_status_id' => 124])->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($pickup);
    }
    public function boradlessdelivery_order($taskIds)
    {
        return $delivery_order = count(DB::table('boradless_dashboard')->where('is_custom_route', 0)->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('competed'))->pluck('task_id'));
    }
    public function boradlessreturn_orders($taskIds)
    {
        return $return_orders = count(DB::table('boradless_dashboard')
            ->whereIn('task_id', $taskIds)
            ->whereIn('task_status_id', [104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->groupBy('tracking_id')
            ->pluck('task_id'));
        //return $return_orders = count(DB::table('boradless_dashboard')->join('sprint__tasks_history','sprint__tasks_history.sprint__tasks_id','=','boradless_dashboard.task_id')
          //  ->whereIn('task_id', $taskIds)->whereIn('status_id', [104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])->groupBy('task_id')->pluck('task_id'));
    }
    public function boradlesshub_return_scan($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')
            ->whereIn('task_id', $taskIds)
            ->whereIn('task_status_id', [104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->whereNotNull('hub_return_scan')
            ->groupBy('tracking_id')
            ->pluck('task_id'));
    }
    public function boradlesshub_return_not_scan($taskIds)
    {
        return $hub_return_not_scan = count(DB::table('boradless_dashboard')
            ->whereIn('task_id', $taskIds)
            ->whereIn('task_status_id', [104, 105, 106, 107, 108, 109, 110, 111, 112, 131, 135, 136, 143])
            ->whereNull('hub_return_scan')
            ->groupBy('tracking_id')
            ->pluck('task_id'));;
    }
    public function boradlessnotscan($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [61])->where('is_custom_route', 0)->pluck('task_id'));
    }
    public function boradlessreattempted($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [ 13])->where('is_custom_route', 0)->pluck('task_id'));
    }
    public function getBoradlessCountsWithCustom($taskIds, $type)
    {
        if (in_array($type, ['all','total'])) {
            $counts['total'] = $this->boradlesstotalOrdersWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'sorted'])) {
            $counts['sorted'] = $this->boradlesssortedWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'picked'])) {
            $counts['pickup'] = $this->boradlesspickupWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'delivered'])) {
            $counts['delivered_order'] = $this->boradlessdelivery_orderWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'return'])) {
            $counts['return_orders'] = $this->boradlessreturn_ordersWithCustom($taskIds);
            $counts['hub_return_scan'] = $this->boradlesshub_return_scanWithCustom($taskIds);
        }
        if (in_array($type, ['all', 'scan'])) {
            $counts['notscan'] = $this->boradlessnotscanWithCustom($taskIds);
        }
        return $counts;
    }
    public function boradlesstotalOrdersWithCustom($taskIds)
    {
        $total = DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->pluck('task_id');
        return count($total);
    }
    public function boradlesssortedWithCustom($taskIds)
    {
        $sorted = DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereNotNull('sorted_at')->pluck('task_id');
        return count($sorted);
    }
    public function boradlesspickupWithCustom($taskIds)
    {
        $pickup = DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereNotNull('picked_up_at')->pluck('task_id');
        return count($pickup);
    }
    public function boradlessdelivery_orderWithCustom($taskIds)
    {
        return $delivery_order = count(DB::table('boradless_dashboard')
            ->whereIn('task_id', $taskIds)
            ->whereIn('task_status_id', $this->getStatusCodes('competed'))
            ->pluck('task_id'));
    }
    public function boradlessreturn_ordersWithCustom($taskIds)
    {
        return $return_orders = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereNotIn('task_status_id', [38, 36, 0])->whereIn('task_status_id', $this->getStatusCodes('return'))->pluck('task_id'));
    }
    public function boradlesshub_return_scanWithCustom($taskIds)
    {
        return $hub_return_scan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id', $this->getStatusCodes('return'))->whereNotNull('hub_return_scan')->pluck('task_id'));
    }
    public function boradlessnotscanWithCustom($taskIds)
    {
        return $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id', [61, 13])->pluck('task_id'));
    }
    public function onOrderCreationBoradlessEntries($sprint,$is_custom_route)
    {

        if (!$sprint instanceof Sprint) {
            return false;
        }
        $this->sprint_id=$sprint->id;
        $task=$sprint->dropoffTask;
        $vendor=$sprint->Vendor;
        $merchantid=$task->taskMerchant;
        $sprint_contact=$task->sprint_contact;
        if (!$task instanceof Task) {
            return false;
        }
        if (!$merchantid instanceof Merchantids) {

            return false;
        }
        $location=$task->location;
        if (!$location instanceof Locations) {

            return false;
        }

        $this->task_id=$task->id;
        $this->eta_time=$task->eta_time;
        $this->creator_id=$sprint->creator_id;
        $this->store_name=$vendor->name;
        $this->tracking_id=$merchantid->tracking_id;
        $this->weight=$merchantid->weight;
        $this->customer_name=$sprint_contact->name;
        $this->address_line_1=$location->address;
        $this->address_line_2=$merchantid->address_line2;
        $this->is_custom_route=$is_custom_route;
        return true;
    }
	public function ExchangeRequestOrder()
    {
        return $this->belongsTo(ExchangeRequest::class,'tracking_id','tracking_id');
    }
	public function SprintTask()
    {
        return $this->belongsTo(Task::class,'task_id','id');
    }
    public function vendor_address()
    {
        return $this->belongsTo(Vendor::class,'creator_id','id');
    }
    public function ReturnedOrder()
    {
        return $this->belongsTo(self::class, 'tracking_id', 'tracking_id')->latest();
    }
    public function getBoradlessCountsForLoop($taskIds, $start, $end)
    {
        $totalRecord = DB::table('boradless_dashboard')->whereNotIn('task_status_id',[38,36])->whereIn('task_id', $taskIds)
            ->get(['sorted_at','picked_up_at','hub_return_scan','delivered_at','returned_at','task_status_id']);
        $total = 0;
        $sorted = 0;
        $pickup = 0;
        $delivered_order = 0;
        $return_orders = 0;
        $hub_return_scan = 0;
        $notscan = 0;
        $reattempted =0;
        $completion_ratio = 0;
        $receivedAtHub = 0;
        foreach ($totalRecord as $record)
        {
//            if ($record->sorted_at != null){
//                $sorted = $sorted + 1 ;
//            }
//            if ($record->picked_up_at != null || $record->delivered_at != null || $record->returned_at != null){
//                $pickup = $pickup + 1 ;
//            }
//            if ($record->delivered_at != null){
//                $delivered_order = $delivered_order + 1 ;
//            }
//            if ($record->returned_at != null && $record->delivered_at == null){
//                $return_orders = $return_orders + 1 ;
//            }
//            if ($record->hub_return_scan != null){
//                $hub_return_scan = $hub_return_scan + 1 ;
//            }
            $total = $total + 1 ;
        }

//        $hub_return_scan = count(DB::table('boradless_dashboard')->where('hub_return_scan','>',$start)->where('hub_return_scan','<',$end)->where(['creator_id' => 477661])->where('is_custom_route', 0)->pluck('id'));
//        $receivedAtHub = count(DB::table('custom_routing_tracking_id')->where('valid_id',1)->where('vendor_id', 477661)->where('created_at','>',$start)->where('created_at','<',$end)->where('is_inbound', 1)->pluck('tracking_id'));


        $sorted = count(DB::table('boradless_dashboard')->where('sorted_at','>',$start)->where('sorted_at','<',$end)->where(['creator_id' => 477661])->where('is_custom_route', 0)->whereNotIn('task_status_id',[38,36])->pluck('id'));
        $pickup = count(DB::table('boradless_dashboard')->where('picked_up_at','>',$start)->where('picked_up_at','<',$end)->where(['creator_id' => 477661])->where('is_custom_route', 0)->whereNotIn('task_status_id',[38,36])->pluck('id'));
        $delivered_order = count(DB::table('boradless_dashboard')->where('delivered_at','>',$start)->where('delivered_at','<',$end)->where(['creator_id' => 477661])->where('is_custom_route', 0)->whereNotIn('task_status_id',[38,36])->pluck('id'));
        $return_orders = count(DB::table('boradless_dashboard')->where('returned_at','>',$start)->where('returned_at','<',$end)->where(['creator_id' => 477661])->where('is_custom_route', 0)->whereNotIn('task_status_id',[38,36])->pluck('id'));

        $sprintIds =  SprintReattempt::where('created_at','>',$start)->where('created_at','<',$end)->pluck('sprint_id');
        $hub_return_scan = count(BoradlessDashboard::where('creator_id', 477661)->whereIn('sprint_id', $sprintIds)->groupBy('tracking_id')->pluck('id'));
        $trackingIds = CustomerRoutingTrackingId::where('valid_id',1)->where('vendor_id', 477661)->where('created_at','>',$start)->where('created_at','<',$end)->where('is_inbound', 1)->pluck('tracking_id');
        $receivedAtHub = count(BoradlessDashboard::whereIn('tracking_id', $trackingIds)->groupBy('tracking_id')->pluck('id'));
//        $hub_return_scan = count(DB::table('boradless_dashboard')->where('hub_return_scan','>',$start)->where('hub_return_scan','<',$end)->where(['creator_id' => 477661])->where('is_custom_route', 0)->pluck('id'));

//        $receivedAtHub = count(DB::table('custom_routing_tracking_id')->where('valid_id',1)->where('vendor_id', 477661)->where('created_at','>',$start)->where('created_at','<',$end)->where('is_inbound', 1)->pluck('tracking_id'));

        $notscan = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->where('task_status_id',61)->whereNotIn('task_status_id',[38,36])->pluck('task_id'));
        $pickupFromStore = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->whereIn('task_status_id',[124])->whereNotIn('task_status_id',[38,36])->pluck('task_id'));
        $reattempted = count(DB::table('boradless_dashboard')->whereIn('task_id', $taskIds)->where('task_status_id', 13)->whereNotIn('task_status_id',[38,36])->pluck('task_id'));

        $counts['total'] = $total;
        $counts['sorted'] = $sorted;
        $counts['pickup'] = $pickup;
        $counts['delivered_order'] = $delivered_order;
        $counts['return_orders'] = $return_orders;
        $counts['hub_return_scan'] = $hub_return_scan;
        $counts['notscan'] = $notscan;
        $counts['pickup_from_store'] = $pickupFromStore;
        $counts['reattempted'] = $reattempted;
        $counts['received_at_hub'] = $receivedAtHub;
        if($pickup > 0){
            $completion_ratio = round(($delivered_order/$pickup)*100,2);
        }
        $counts['completion_ratio'] = $completion_ratio;
        return $counts;
    }
    public function Sprint()
    {
        return $this->belongsTo(Sprint::class,'sprint_id','id');
    }

}
