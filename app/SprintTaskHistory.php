<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\BasicModelFunctions;

class SprintTaskHistory extends Model
{
	 use BasicModelFunctions;
	     public $timestamps = false;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'sprint__tasks_history';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        "sprint__tasks_id",
        "sprint_id",
        "status_id",
        "active",
        "resolve_time",
        "date",
        "created_at"
    ];

    /**
    * Scope a query to only include active users.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
	
	 /*   public function getCounts($sprintIds){
        $counts['total_orders'] = $this->totalOrders($sprintIds);
        $counts['pickup_orders'] = $this->pickupOrders($sprintIds);
        $counts['sorted_orders'] = $this->sortedOrders($sprintIds);
        $counts['delivered_orders'] = $this->deliveredOrders($sprintIds);
        return $counts;
    }

   
    public function totalOrders($sprintIds)
    {
        $total = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->distinct('sprint_id')->pluck('sprint_id');
        return count($total);
    }

    public function pickupOrders($sprintIds)
    {
        $pickup_orders = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->where('status_id',121)->distinct('sprint_id')->pluck('sprint_id');
        return count($pickup_orders);
    }

    public function sortedOrders($sprintIds)
    {
        $sorted_orders = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->where('status_id',133)->distinct('sprint_id')->pluck('sprint_id');
        return count($sorted_orders);
    }

    public function deliveredOrders($sprintIds)
    {
        $delivered_orders = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->whereIn('status_id',[17,113,114,116,117,118,138,139])->distinct('sprint_id')->pluck('sprint_id');
        return count($delivered_orders);
    }*/
	public function getCounts($sprintIds){
        $counts['total'] = $this->totalOrders($sprintIds);
        //$counts['otd'] = 0;
        $counts['atstore'] = $this->pickup_from_store($sprintIds);
        $counts['athub'] = $this->at_hub_processing($sprintIds);
        $counts['outfordelivery'] = $this->out_for_delivery($sprintIds);
        $counts['deliveredorder'] = $this->delivery_time($sprintIds);
        $counts['at_unattemp'] = $this->at_unattemp($sprintIds);
        return $counts;



    }


    public function totalOrders($sprintIds)
    {
        $total = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->distinct('sprint_id')->pluck('sprint_id');
        return count($total);
    }


    public function pickup_from_store($sprintIds)
    {
        $pickup_from_store = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->where('status_id',125)->distinct('sprint_id')->pluck('sprint_id');
        return count($pickup_from_store);
    }

    public function at_hub_processing($sprintIds)
    {
        $at_hub_processing = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->where('status_id',124)->distinct('sprint_id')->pluck('sprint_id');
        return count($at_hub_processing);
    }

    public function out_for_delivery($sprintIds)
    {
        $out_for_delivery = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->where('status_id',121)->distinct('sprint_id')->pluck('sprint_id');
        return count($out_for_delivery);
    }

   /* public function delivery_time($sprintIds)
    {
        $delivery_time = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->whereIn('status_id',[17,113,114,116,117,118,132,138,139,144,131,104,105,106,107,108,109,110,111,112,131,135,136])->distinct('sprint_id')->pluck('sprint_id');
        return count($delivery_time);
    }*/
	  public function delivery_time($sprintIds)
    {
        $status_array = array_merge($this->getStatusCodes('competed'),$this->getStatusCodes('return'));
        $delivery_time = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->whereIn('status_id',$status_array)->distinct('sprint_id')->pluck('sprint_id');
        return count($delivery_time);
    }

    public function at_unattemp($sprintIds)
    {
        $at_unattemp = DB::table('sprint__tasks_history')->whereIn('sprint_id',$sprintIds)->where('status_id',13)->distinct('sprint_id')->pluck('sprint_id');
        return count($at_unattemp);
    }

    public function atHubProcessingSecond($sprint_id)
    {
        // gating current routs tasks ids
        return  DB::table('sprint__tasks_history')->select((DB::raw("MIN(CASE WHEN status_id IN (124) AND sprint_id = ".$sprint_id." THEN CONVERT_TZ(created_at,'UTC','America/Toronto') ELSE NULL END) as athub")))->first();
    }



}