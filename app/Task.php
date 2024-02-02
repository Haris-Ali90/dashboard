<?php

namespace App;

use App\Http\Traits\BasicModelFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Task extends Model
{
    use BasicModelFunctions;
    protected $table = 'sprint__tasks';

    /**
     * Scope a query to only include active tasks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to only include not deleted  tasks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted_at', null);
    }

	public function taskMerchants()
    {
        return $this->belongsTo(MerchantIds::class,'id','task_id')->where('tracking_id','!=',null);
    }

    public function taskRouteLocation()
    {
        return $this->belongsTo(JoeyRouteLocations::class,'id','task_id')->whereNull('deleted_at');
    }

    public function task_Location()
    {
        return $this->belongsTo(Locations::class, 'location_id', 'id');
    }


    public function taskMerchant()
    {
        return $this->belongsTo(MerchantIds::class,'task_id','task_id')
            ->select(DB::raw("CONVERT_TZ(FROM_UNIXTIME(scheduled_duetime),'UTC','America/Toronto') as scheduled_duetime"),
                "merchant_order_num","tracking_id","start_time","end_time");
    }

    public function taskJoeyRouteLocation()
    {
        return $this->belongsTo(JoeyRouteLocations::class,'task_id','task_id') ->select(DB::raw("CONCAT('R-',route_id,'-',ordinal) as route"))
            ->whereNull('deleted_at');

    }

    public function taskSprintConfirmation()
    {
        return $this->belongsTo(SprintConfirmation::class,'task_id','task_id')
            ->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }
	
    public function taskContact()
    {
        return $this->belongsTo(SprintContact::class,'contact_id','id')->whereNull('deleted_at');
    }


    public function sprintConfirmations()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->orderBy('id','desc')->whereNotNull('attachment_path');
    }

    public function getDeliveryTimeAttribute()
    {


        return TaskHistory::where('sprint__tasks_id',$this->task_id)->whereIn('status_id',[17,113,114,116,117,118,138,139,144])->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS delivery_time"))->first();
    }

    public function getPickedHubTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->task_id)->where('status_id',121)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS picked_hub_time"))->first();
    }

    public function getSorterTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->task_id)->where('status_id',133)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS sorted_time"))->first();
    }
	
	#grocery
    public function groceryTaskMerchants()
    {
        return $this->belongsTo(MerchantIds::class,'id','task_id')
            ->select(
                "merchant_order_num","end_time");
    }

    public function getGroceryDeliveryTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->id)->whereIn('status_id',[17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS delivery_time"))->first();
    }

    public function sprintConfirmationsImage()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->where('title','Upload Image')->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }

    public function sprintConfirmationsSignature()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->where('title','Signature')->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    }
	
	#Loblaws Calgary

    public function loblawsTaskMerchants()
    {
        return $this->belongsTo(MerchantIds::class,'id','task_id')
            ->select(DB::raw("CONVERT_TZ(FROM_UNIXTIME(scheduled_duetime),'UTC','America/Edmonton') as scheduled_duetime"),"merchant_order_num","start_time","end_time");
    }

    public function getLablawsArrivalTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->id)->where('status_id',67)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Edmonton') AS arrival_time"))->first();
    }


    public function getLablawsDeliveryTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->id)->whereNotNull('resolve_time')->whereIn('status_id',[17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->orderBy('created_at','desc')->select(DB::raw("CONVERT_TZ(sprint__tasks_history.resolve_time,'UTC','America/Edmonton') AS delivery_time"))->first();
    }

    public function LablawsSprintConfirmationsImage()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->where('title','Upload Image')->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }

    public function LablawsSprintConfirmationsSignature()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->where('title','Signature')->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }
	
	public function loblawsContacts()
    {
        return $this->belongsTo(SprintContact::class, 'contact_id', 'id')->select('name');
    }

    #Loblaws Home Delivery
    public function getLablawsHomeDeliveryTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->id)->whereNotNull('resolve_time')->whereIn('status_id',[17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->orderBy('created_at','desc')->select(DB::raw("CONVERT_TZ(sprint__tasks_history.resolve_time,'UTC','America/Toronto') AS delivery_time"))->first();
    }
	
	 public function getLablawsHomeArrivalTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->id)->where('status_id',67)->select(DB::raw("CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto') AS arrival_time"))->first();
    }


    public function loblawsHomeTaskMerchants()
    {
        return $this->belongsTo(MerchantIds::class,'id','task_id')
            ->select(DB::raw("CONVERT_TZ(FROM_UNIXTIME(scheduled_duetime),'UTC','America/Toronto') as scheduled_duetime"),"merchant_order_num","start_time","end_time");
    }
	
	
	public function  Merchantids()
    {
        return $this->hasOne(MerchantIds::class,'task_id','id');
    }

    public function getDueTimeDateTimeFormatAttribute()
    {
        $date = Carbon::createFromTimestamp($this->due_time)->timezone('America/Toronto')
        ->format('Y-m-d H:i:s');
        return $date;
    }

    public function getEtaTimeDateTimeFormatAttribute()
    {
        $date = Carbon::createFromTimestamp($this->eta_time)->timezone('America/Toronto')
        ->format('Y-m-d H:i:s');
        return $date;
    }

    public function getEtcTimeDateTimeFormatAttribute()
    {
        $date = Carbon::createFromTimestamp($this->etc_time)->timezone('America/Toronto')
        ->format('Y-m-d H:i:s');
        return $date;
    }
	
	    public function GoodFoodSprintConfirmationsImage()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->where('title','Upload Image')->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }
	
    public function GoodFoodSprintConfirmationsSignature()
    {
        return $this->belongsTo(SprintConfirmation::class,'id','task_id')->where('title','Signature')->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }
	
	    public function goodFoodContacts()
    {
        return $this->belongsTo(SprintContact::class, 'contact_id', 'id')->select('name');
    }

    public function getGoodFoodDeliveryTimeAttribute()
    {
        return TaskHistory::where('sprint__tasks_id',$this->id)->whereIn('status_id',[17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->orderBy('created_at','desc')->select(DB::raw("CONVERT_TZ(sprint__tasks_history.resolve_time,'UTC','America/Toronto') AS delivery_time"))->first();
    }
	
	    public function GoodFoodTaskMerchants()
    {
        return $this->belongsTo(MerchantIds::class,'id','task_id')
            ->select(DB::raw("CONVERT_TZ(FROM_UNIXTIME(scheduled_duetime),'UTC','America/Toronto') as scheduled_duetime"),"merchant_order_num","start_time","end_time");
    }
    public function sprintTaskHistoryDetail()
    {
        return $this->hasMany(SprintTasksHistory::class,'sprint_id','sprint_id')
        // ->whereNotIn('status_id',[38,17])->orderBy('created_at');
        ->whereNotIn('status_id',[38])->orderBy('created_at');

    }
	
	public function OldSprintDetail()
    {
        return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    }
	
}

