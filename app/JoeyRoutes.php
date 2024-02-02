<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Joey;
use App\Http\Traits\BasicModelFunctions;



class JoeyRoutes extends Model
{
    use BasicModelFunctions;

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'joey_routes';

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
        "joey_id",
        "created_at",
        "updated_at",
        "deleted_at",
        "date",
        "total_travel_time",
        "total_distance",
    ];


    public function joey()
    {
        return $this->belongsTo(Joey::class, 'joey_id');
    }
    /**
     * Get joey data.
     */
    /*public function Joey()
    {
        return $this->belongsTo(Joeys::class,'joey_id', 'id');
    }*/

    /**
     * Get joey routs locations.
     */
    public function RouteLocarions()
    {
        return $this->hasMany( JoeyRouteLocations::class,'route_id', 'id');
    }

 public function joeyDetail()
    {
        return $this->belongsTo(Joey::class,'joey_id','id')->whereNull('deleted_at')
            ->select(DB::raw("CONCAT(joeys.first_name,' ',joeys.last_name,'(',joey_routes.joey_id,')') as joey"));
    }

    public function FlagHistoryByRouteID()
    {
        return $this->hasOne(FlagHistory::class, 'route_id','id')->where('flagged_type','route')->whereNull('unflaged_date');
    }

    /**
     * Get Total Tasks Ids in this Route .
     */
    public function GetAllTaskIds()
    {
        // gating current routs tasks ids
        return $this->RouteLocarions()
            ->whereNull('deleted_at')
            ->pluck('task_id')
            ->toArray();
    }

    /**
     * Get Total Numbers Of Order Drops in this Route .
     */
    public function TotalOrderDropsCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();

        return Task::whereNotIn('status_id', [36])
            ->whereIn('sprint__tasks.id', $tasks_ids)
            ->whereNull('deleted_at')
            ->count();
        //return $this->RouteLocarions()->whereNull('deleted_at')->count();
    }

    public function getLastDeliveredTime($routeId)
    {
        $taskIds = JoeyRouteLocations::where('route_id', $routeId)->pluck('task_id');
        $taskHistoryTime = TaskHistory::whereIn('sprint__tasks_id', $taskIds)->whereIn('status_id', [17, 113, 114, 116, 117, 118, 132, 138, 139, 144])->orderBy('id', 'DESC')->first();
        return (isset($taskHistoryTime->created_at)) ? $taskHistoryTime->created_at : '';

    }

    public function getLastActivityTime($routeId)
    {
        $taskIds = JoeyRouteLocations::where('route_id', $routeId)->pluck('task_id');
        $taskHistoryTime = TaskHistory::whereIn('sprint__tasks_id', $taskIds)->orderBy('id', 'DESC')->first();
        return (isset($taskHistoryTime->created_at)) ? $taskHistoryTime->created_at : '';

    }

    /**
     * Get Total Numbers Of Sorted Orders in this Route .
     */
           public function TotalSortedOrdersCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();

        // getting route creation datetime
        $ceated_at = $this->date;

        // getting status codes
        $status_codes = implode(',',$this->getStatusCodes('sort'));

        return SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)
            ->join('sprint__sprints','sprint__tasks_history.sprint_id', '=', 'sprint__sprints.id')
            ->where('sprint__tasks_history.status_id',$status_codes)
            ->whereNotIn('sprint__sprints.status_id', [36])
            ->where('sprint__sprints.deleted_at', null)
            //->Active()
            ->where('sprint__tasks_history.date' ,'>=',$ceated_at)
            ->orderBy('sprint__tasks_history.date', 'DESC')
            //->groupBy('status_id')
			->distinct('sprint__tasks_history.sprint__tasks_id')
            ->count('sprint__tasks_history.sprint__tasks_id');
    }


    /**
     * Get Total Numbers Of Orders Completed in this Route .
     */
    public function TotalOrderDropsCompletedCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();

        // getting status codes
        $status_codes = $this->getStatusCodes('competed');

        return Task::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__sprints.status_id',$status_codes)
            ->whereNotIn('sprint__tasks.status_id', [36])
            ->whereIn('sprint__tasks.id', $tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->count();


        /*return Task::/*SprintTaskHistory::*/
             //whereIn('sprint__tasks_id',$tasks_ids)
             //whereIn('id',$tasks_ids)
            //->whereIn('status_id',[17,118,117,106,107,108,111,113,114,116])
            /*->Active()
            //->orderBy('date', 'DESC')
            //->groupBy('status_id')
            //->count();*/
            //->where('type','dropoff')
            //->Active()
            //->NotDeleted()
            //->count();*/
    }

    /**
     * Get Total Numbers Of Orders Picked in this Route .
     */
    public function TotalOrderPickedCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();

        // getting route creation datetime
        $ceated_at = $this->date;

        // getting status codes
        $status_codes = implode(',',$this->getStatusCodes('pickup'));

        return SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)
            ->join('sprint__sprints','sprint__tasks_history.sprint_id', '=', 'sprint__sprints.id')
            ->where('sprint__tasks_history.status_id',$status_codes)
            ->where('sprint__sprints.deleted_at', null)
            ->whereNotIn('sprint__sprints.status_id', [36])
            ->where('sprint__tasks_history.date' ,'>=',$ceated_at)
            ->orderBy('sprint__tasks_history.date', 'DESC')
            //->groupBy('status_id')
			->distinct('sprint__tasks_history.sprint__tasks_id')
           // ->Active()
            ->count('sprint__tasks_history.sprint__tasks_id');
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route .
     */
    public function TotalOrderReturnCount()
    {

          // getting status codes
        $status_codes = $this->getStatusCodes('return');


        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        return Task::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__sprints.status_id',$status_codes)
            ->whereNotIn('sprint__tasks.status_id', [36])
            ->whereIn('sprint__tasks.id', $tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->count();


        // gating current routs tasks ids
        /*$tasks_ids = $this->GetAllTaskIds();
        return SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)
            ->whereIn('status_id',$status_codes)
            ->Active()
            ->orderBy('date', 'DESC')
            ->distinct('sprint__tasks_id')
            //->groupBy('status_id')
            ->count();
            //->where('type','dropoff')
            //->Active()
            //->NotDeleted()
            //->count();*/
    }


    /**
     * Get joey routs locations.
     */
    public function returnRoute()
    {
        return $this->hasMany( ReturnReattemptProcess::class,'route_id', 'id');
    }

    /**
     * Get Total Tasks Ids in this Route .
     */
    public function GetAllSprintIds()
    {
        // gating current routs tasks ids
        return $this->returnRoute()
            ->whereNull('deleted_at')
            ->distinct('sprint_id')
            ->pluck('sprint_id')
            ->toArray();
    }
    /**
     * Get Total Numbers Of Orders at hub scan in this Route .
     */
    public function TotalOrderAtHubScanCount()
    {
        $sprint_ids = $this->GetAllSprintIds();
        return JoeyRouteLocations::join('sprint__tasks', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
            ->join('sprint_reattempts', 'sprint__tasks.sprint_id', '=', 'sprint_reattempts.sprint_id')
            ->whereNull('joey_route_locations.deleted_at')
            ->where('joey_route_locations.route_id',$this->id)
            ->whereIn('sprint_reattempts.sprint_id', $sprint_ids)->count();
        //return SprintReattempt::whereIn('sprint_id', $sprint_ids)->count();
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route .
     */
    public function TotalOrderNotScanCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();

        // getting status codes
        $status_codes = $this->getStatusCodes('unattempted');

        return Task::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__sprints.status_id',$status_codes)
            ->whereNotIn('sprint__tasks.status_id', [36])
            ->whereIn('sprint__tasks.id', $tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->count();

        /*return Task::whereIn('id',$tasks_ids)
            ->whereIn('status_id',[61,13])
            ->where('type','dropoff')
            ->Active()
            ->NotDeleted()
            ->count();*/
        //return $this->TotalOrderPickedCount() - ($this->TotalOrderDropsCompletedCount() + $this->TotalOrderReturnCount());
    }


    /**
     * Get Total Time Of Orders FirstDropScan in this Route .
     */
    public function FirstDropScan()
    {

        // getting status codes
        $status_codes = $this->getStatusCodes();
        $status_codes = array_merge($status_codes['competed'],$status_codes['return']);


        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        $data = SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)
        ->whereIn('status_id',$status_codes)->orderBy('created_at','asc')->Active()->first();
        if($data != null)
        {
            $data->toArray();
            return $data['created_at'];
        }
        return 'Not scan yet';
//        ->toArray();
//        return $data['created_at'];

    }

    /**
     * Get Total Time Of Orders LastDropScan in this Route .
     */
    public function LastDropScan()
    {

        // getting status codes
        $status_codes = $this->getStatusCodes();
        $status_codes = array_merge($status_codes['competed'],$status_codes['return']);

        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        $data = SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)
        ->whereIn('status_id',$status_codes)
        ->orderBy('created_at','desc')->Active()->first();
        if($data != null)
        {
            $data->toArray();
            return $data['created_at'];
        }
        return 'Not scan yet';
        //return $data['created_at'];

    }

    /**
     * Get Total Time Of Orders LastDropScan in this Route .
     */
    public function FirstSortScan()
    {
        // getting status codes
        $status_codes = implode(',',$this->getStatusCodes('sort'));

        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        $data = SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)->where('status_id',$status_codes)->orderBy('created_at','asc')->Active()->first()->toArray();
        return $data['created_at'];
    }

    /**
     * Get Time Of First Pickup Scan Of Order in this Route .
     */
    public function FirstPickUpScan()  // first pick up scan from hub
    {
        // getting status codes
        $status_codes = implode(',',$this->getStatusCodes('pickup'));

        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        $data = SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)
            ->where('status_id',$status_codes)
            ->where('active',1)
            ->orderBy('created_at','asc')
            ->first();
        if($data != null)
        {
            $data->toArray();
            return $data['created_at'];
        }
        return 'Not scan yet';

    }


    /**
     * Get Time Of First Pickup Scan Of Order in this Route .
     */
    public function EstimatedTime()  // first pick up scan from hub
    {
        $data = JoeyRouteLocations::where('route_id','=', $this->id)
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(finish_time)-TIME_TO_SEC(arrival_time))) AS duration')
            ->pluck('duration')->toArray();
        return $data[0];

    }
	
	 public function TotalOrderUnattemptedCount()
    {
		if($this->TotalOrderPickedCount() >= $this->TotalOrderDropsCompletedCount())
		{
            return abs($this->TotalOrderPickedCount() - $this->TotalOrderDropsCompletedCount() - $this->TotalOrderReturnCount());
		}
		else
			return 0;
    }



    /**
     * Is Custom Or Not .
     */
    public function isCustom()
    {

        if($this->zone == null)
        {
            return 'Yes';
        }
        else{
            $checkZone = \DB::table("zones_routing")->where('id',$this->zone)->whereNotNull('is_custom_routing')->first();
            if($checkZone)
            {
                return 'Yes';
            }
            else
            {
                return 'No';
            }

        }

    }
    public function getDropPerHour()
    {
        $locations=$this->getTaskIds();
                // dd($locations);
                $deliveryOrder=TaskHistory::whereIn('sprint__tasks_id', $locations)
                ->whereIn('status_id',[17,113,114,116,117,118,132,138,139,144,104,105,106,107,108,109,110,111,112,131,135,136])
                ->orderby('date')
                ->groupby('sprint__tasks_id');
    
                // dd($deliveryOrder->get());
            
                $count=[];
               $totalcount=0;
                $i=0;
                $key=null;
                foreach ($deliveryOrder->get() as $order)
                {
                    $returnStatus=TaskHistory::whereIn('status_id',[104,105,106,107,108,109,111,131,135])
                    ->where('sprint__tasks_id','=',$order->sprint__tasks_id)->first();
                    if($returnStatus!=null)
                    {
                        continue;
                    }
                    if($key==null)
                    {
                        $key=$order->date;
                        $count[$key]=1;
                        $i++;
                    }
                    else
                    {
                                
                        $first_date = new \DateTime( date("Y-m-d H:i:s", strtotime($key)));
                        $second_date =new \DateTime(date('Y-m-d H:i:s',strtotime($order->date))); 
                        $difference = $first_date->diff($second_date);
                        if($difference->h>0)
                        {
                            $key=$order->date;
                            $count[$key]=1;
                            $i++;
                        }
                        else
                        {
                            $count[$key]++;
                        }
    
                    }
                    $totalcount++;
    
                }
                if($i!=0)
                {
                    return round($totalcount/$i);
                }
                else
                {
                    return 0;
                }
                
    }

    public function getTaskIds()
    {
        
    return $this->hasMany(new JoeyRouteLocations(),'route_id')->whereNull('joey_route_locations.deleted_at')->pluck('task_id')->toArray();

    }


	 public function zoneDetail()
    {
        return $this->belongsTo(ZoneRouting::class,'zone','id')->whereNull('deleted_at');
    }

    /**
     * Get the ZoneType.
     */
    public function ZoneType()
    {
        return $this->hasOneThrough(
            ZonesTypes::class,
            ZoneRouting::class,
            'id', // Foreign key on users table...
            'id', // Foreign key on history table...
            'zone', // Local key on suppliers table...
            'zone_type' // Local key on users table...
        );
    }

}