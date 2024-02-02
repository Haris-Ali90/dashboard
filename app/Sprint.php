<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\BasicModelFunctions;

class Sprint extends Model
{
use BasicModelFunctions;
    protected $table = 'sprint__sprints';

public function ctcOrders($sprintIds,$from_date, $to_date)
    {
        return Sprint::join('sprint__tasks','sprint__sprints.id','=','sprint__tasks.sprint_id')
            ->join('merchantids','sprint__tasks.id','=','merchantids.task_id')
            ->whereBetween(\DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"), [$from_date, $to_date])
            ->whereIn('sprint__sprints.id', $sprintIds)
            ->where('sprint__tasks.type','dropoff')
            ->where('sprint__tasks.deleted_at',null);

    }


    public function tasks()
    {
        return $this->hasMany(Task::class, 'sprint_id', 'id');
    }

    public function sprintTasksFlag()
    {
        return $this->belongsTo(Task::class, 'id', 'sprint_id')->where('type','dropoff');
    }
	
	public function get_latest_task()
    {
        return $this->tasks->where('type', 'dropoff')
            //->orderBy('ordinal','asc')
            ->sortByDesc('ordinal')
            ->first();
    }

    public function get_dropoff_latest_task()
    {
        return $this->tasks->where('type','dropoff')->orderby('id','DESC')->first();
    }

    public function sprintTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id');
    }


    public function sprint_Tasks()
    {
        return $this->hasMany(Task::class, 'sprint_id', 'id');
    }



    public function sprintCtcTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','dropoff')->orderby('id','DESC')->select('id','status_id','ordinal','location_id','contact_id',\DB::raw("CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') as eta_time"));
    }



   /* public function sprintVendor()
    {
        return $this->belongsTo(Vendor::class,'creator_id','id')->whereNull('deleted_at');
    }*/
	
	    public function sprintVendor()
    {
        return $this->belongsTo(CtcVendor::class,'creator_id','vendor_id')->whereNull('deleted_at');
    }
	
	

    /**
     * Get Sprint Task History.
     */
    public function SprintTaskHistory()
    {
        return $this->hasMany( SprintTaskHistory::class,'sprint_id', 'id');
    }




    public function pickupFromStore()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id=125 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as pickup")))->where('status_id',125)->orderBy('date','ASC')->limit(2)->first();
    }


    public function pickupFromStoreOtd($otd_date)
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id=125 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as pickup")))->where('status_id',125)->orderBy('date','ASC')->limit(2)->first();
    }

    public function atHubProcessing()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (133) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->first();
    }

    public function atHubProcessingOtd()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (124) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->where('status_id',124)->orderBy('date','ASC')->limit(2)->first();
    }

    public function atHubProcessingFirst()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (124) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->where('status_id',124)->orderBy('date','ASC')->limit(2)->first();
    }



    public function ctcAtHubProcessingFirst()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN (124) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->where('status_id',124)->orderBy('date','ASC')->limit(2)->first();
    }

    public function outForDelivery()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id=121 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as outdeliver")))->first();
    }


    public function sorterTime()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id=133 THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as sortertime")))->first();
    }

/*    public function deliveryTime()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw("MAX(CASE WHEN status_id IN(17,113,114,116,117,118,132,138,139,144,131,104,105,106,107,108,109,110,111,112,131,135,136) THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as delivery_time")))->first();
    }*/
	
	 public function montrealDeliveryTime()
    {
        $deliver_status = implode(',',$this->getStatusCodes('competed'));
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw('MAX(CASE WHEN status_id IN('.$deliver_status.') THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();
    }
	    public function deliveryTime()
    {
        $deliver_status = implode(',',$this->getStatusCodes('competed'));
        $return_status = implode(',',$this->getStatusCodes('return'));
        // gating current routs tasks ids
//        return $this->SprintTaskHistory()
//            ->select((DB::raw('MAX(CASE WHEN status_id IN('.$deliver_status.$return_status.') THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();

        return $this->SprintTaskHistory()
            ->select((DB::raw('MAX(CASE WHEN status_id IN(17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143,141) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();
    }

    public function deliveryTimeOTD()
    {
        // gating current routs tasks ids
        return $this->SprintTaskHistory()
            ->select((DB::raw('MAX(CASE WHEN status_id IN(17, 113, 114, 116, 117, 118, 132, 138, 139, 144, 131, 101, 102, 103, 104, 105, 106, 107, 108, 109, 111, 112, 131, 135, 136, 143) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as delivery_time')))->first();
    }


    public function actualDeliveryTime()
    {
        return $this->SprintTaskHistory()
            ->select((DB::raw('MAX(CASE WHEN status_id IN (17, 113, 114, 116, 117, 118, 132, 138, 139, 144) THEN CONVERT_TZ(created_at,"UTC","America/Toronto") ELSE NULL END) as actual_delivery')),
                (DB::raw('MAX(CASE WHEN status_id IN ( 113, 114, 116, 117, 118, 132, 138, 139, 144) THEN status_id ELSE NULL END) as status_id')))->first();
    }

    public function sprintReattempts()
    {
        return $this->belongsTo(SprintReattempt::class,'id','sprint_id');
    }

    public function sprintReattemptsOTD()
    {
        return $this->belongsTo(SprintReattempt::class,'id','reattempt_of');
    }

    public function geCtcCounts($sprintIds)
    {
        $history = new SprintTaskHistory();
        return $history->getCounts($sprintIds);
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
        $totalOrders = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->pluck('id');
        return count($totalOrders);
    }

    public function picked_up($sprintIds)
    {
        $picked_up = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->where('status_id',125)->pluck('id');
        return count($picked_up);
    }

    public function at_hub($sprintIds)
    {
        $at_hub = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->whereIn('status_id',[124,13,120])->pluck('id');
        return count($at_hub);
    }

    public function at_store($sprintIds)
    {
        $at_store = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->where('status_id',61)->pluck('id');
        return count($at_store);
    }

    public function sorted_order($sprintIds)
    {
        $sorted_order = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->where('status_id',133)->pluck('id');
        return count($sorted_order);
    }

    public function out_for_delivery($sprintIds)
    {
        $out_for_delivery = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->where('status_id',121)->pluck('id');
        return count($out_for_delivery);
    }

    public function delivery_order($sprintIds)
    {
        $delivery_order = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->whereIn('status_id',$this->getStatusCodes('competed'))->pluck('id');
        return count($delivery_order);
    }

    public function returned($sprintIds)
    {
        $returned = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->whereIn('status_id',$this->getStatusCodes('return'))
            ->where('status_id','!=',111)->pluck('id');
        return count($returned);
    }

    public function returned_to_merchant($sprintIds)
    {
        $returned_to_merchant = DB::table('sprint__sprints')->whereIn('id',$sprintIds)->where('status_id',111)->pluck('id');
        return count($returned_to_merchant);
    }
	
	#grocery
    public function joey()
    {
        return $this->belongsTo(Joey::class, 'joey_id')
            ->select('id',DB::raw("concat(first_name,' ',last_name) as joey_name"));
    }
	
	#grocery
    public function joeyName()
    {
        return $this->belongsTo(Joey::class, 'merchant_joey_id','id')
            ->select('id',DB::raw("concat(first_name,' ',last_name) as joey_name"));
    }

    public function groceryTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','pickup')->select(\DB::raw("CONVERT_TZ(from_unixtime(eta_time),'UTC','America/Toronto') as arrival_eta"));
    }


    public function getDepartureTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',15)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS departure_time"))->first();
    }

    public function getArrivalTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',67)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS arrival_time"))->first();
    }
	
	#Loblaws Calgary
    public function loblawsJoey()
    {
        return $this->belongsTo(Joey::class, 'joey_id')
            ->select('id',DB::raw("concat(first_name,' ',last_name) as joey_name"));
    }
	
	    public function loblawsTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','pickup')->select(\DB::raw("CONVERT_TZ(from_unixtime(due_time),'UTC','America/Edmonton') as arrival_eta"));
    }

    public function loblawsContactTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','pickup');
    }


    public function getLoblawsDepartureTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',15)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Edmonton') AS departure_time"))->first();
    }

    public function getLoblawsArrivalTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',67)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Edmonton') AS arrival_time"))->first();
    }
	
    /**
     * Get Sprint Notes.
     */
    public function SprintNotes()
    {
        return $this->hasMany( Notes::class,'object_id', 'id');
    }

    #Loblaws Home Delivery
    public function loblawsHomeTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','pickup')->select(\DB::raw("CONVERT_TZ(from_unixtime(due_time),'UTC','America/Toronto') as arrival_eta"));
    }

     public function getLoblawsHomeDepartureTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',15)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS departure_time"))->first();
    }

    public function getLoblawsHomeArrivalTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',67)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS arrival_time"))->first();
    }


	public function SprintLastDropOffTask()
    {
        return $this->hasOne(Task::class, 'sprint_id', 'id')
            ->where('type', 'dropoff')
            ->orderby('id', 'DESC');
    }

    public function SprintFirstPickUpTask()
    {
        return $this->hasOne(Task::class, 'sprint_id', 'id')
            ->where('type', 'pickup')
            ->orderby('id', 'ASC');
    }

    public function JoeyObject()
    {
        return $this->belongsTo(Joey::class, 'joey_id');
    }
	
	

    /**
     * Get Sprint Order Code.
     */
    public function OrderCodes()
    {
        return $this->belongsToMany(OrderCode::class, 'order_assigned_code', 'sprint_id', 'code_id');
    }
	
	//Relation With Vendor
    public function Vendor()
    {
        return $this->belongsTo(Vendor::class,'creator_id' , 'id');
    }

  public function GoodFoodTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','pickup')->select(\DB::raw("CONVERT_TZ(from_unixtime(due_time),'UTC','America/Toronto') as arrival_eta"));
    }
	
	    public function getGoodFoodDepartureTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',15)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS departure_time"))->first();
    }
	
	    public function getGoodFoodArrivalTimeAttribute()
    {
        return TaskHistory::where('sprint_id',$this->id)->where('status_id',67)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS arrival_time"))->first();
    }

    public function goodFoodJoey()
    {
        return $this->belongsTo(Joey::class, 'joey_id')
            ->select('id',DB::raw("concat(first_name,' ',last_name) as joey_name"));
    }
	
	    public function GoodFoodContactTasks()
    {
        return $this->hasone(Task::class, 'sprint_id', 'id')->where('type','pickup');
    }
	
}

