<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouteHistory extends Model {

    const AssignRopute = 0;
    const TransferRoute = 1;

    private $Statuses = ["assign" => 0  ,"transfer" => 1, "completed" => 2 ,"pickup"=> 3 , "return"=> 4];
    private $FlagDataByJoey = null;
    private $JoeyTasksIds = [];
    private $JoeyTrackingids = [];
    private $JoeyCompletedDrops = 0;
    private $JoeyReturnDrops = 0;
    private $JoeyPickUpOrders = 0;
    private $JoeyFirstPickupTime = '';
    private $JoeyFirstDropTime = '';
    private $JoeyLastDropTime = '';
    private $JoeyActualTotalKM = 0;
    private $IsCalculationDoneByJoey = false;

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $table = 'route_history';
    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function getJoey()
    {
        return $this->hasOne(Joey::class,'id','joey_id');
    }

    /**
     * Model filter scopes
     *
     */

    public function scopeRouteLocationIdExsit($query)
    {
        return $query->where('route_location_id','!=',null);
    }

    /**
     * ORM Relation
     *
     * @var array
     */



    /**
     * Get joey data.
     */
    public function Joey()
    {
        return $this->belongsTo(Joey::class,'joey_id', 'id');
    }

    /**
     * Get JoeyRoute data.
     */
    public function JoeyRoute()
    {
        return $this->belongsTo(JoeyRoutes::class,'route_id', 'id');
    }

    /**
     * Get Sprint task history data.
     */
    public function SprintTaskHistoryLatest()
    {
        return $this->belongsTo(SprintTaskHistory::class,'task_id', 'sprint__tasks_id')->orderBy('id','DESC');
    }

    /**
     * Get JoeyRoute data.
     */
    public function JoeyRouteLocation()
    {
        return $this->belongsTo(JoeyRouteLocations::class,'route_id', 'route_id')->whereNull('deleted_at');
    }

    public function JoeyRouteLocationTest()
    {

        return $this->belongsTo(JoeyRouteLocations::class,'route_location_id', 'id')->whereNull('deleted_at');

    }

    /**
     * Get All JoeyRoute locataion  data.
     */
    public function JoeyRouteLocationsByRouteID()
    {
        return $this->hasMany(JoeyRouteLocations::class,'route_id', 'route_id');
    }

    /**
     * Get task .
     */
    public function SprintTask()
    {
        return $this->belongsTo(Task::class,'task_id', 'id');
    }

    /**
     * Get manual adjustment by route.
     */
    public function RouteManualAdjustmentByJoey()
    {
        return $this->hasMany(PayoutManualAdjustment::class,'route_id', 'route_id')
            ->where('joey_id',$this->joey_id)
            ;
    }

    /**
     * Get the ZoneType.
     */
    public function ZoneRouting()
    {
        return $this->hasOneThrough(
            ZoneRouting::class,
            JoeyRoutes::class,
            'id', // Foreign key on users table...
            'id', // Foreign key on history table...
            'route_id', // Local key on suppliers table...
            'zone' // Local key on users table...
        );
    }

    /**
     * Get All tasks ids on current joey data.
     */
    public function JoeyTasksIds()
    {
        $retrun_data = [];
        $query = $this->hasMany(self::class,'joey_id', 'joey_id')
            ->has('JoeyRouteLocation')
            ->where('route_id',$this->route_id)
            ->get();

        // checking the data is exist
        if($query != null)
        {
            $retrun_data = $query->pluck('task_id')->toArray();
            $retrun_data = array_filter(array_unique($retrun_data));
        }

        //setting current task ids
        $this->JoeyTasksIds = $retrun_data;

        return $retrun_data;
    }


    // get cached task ids
    public function GetCachedJoeyTasksIds()
    {
        // checking the joey ids cached
        if(count($this->JoeyTasksIds) == 0)
        {
            $this->JoeyTasksIds();
        }

        return $this->JoeyTasksIds;

    }

    // get current joey all task Trackingids
    public function GetJoeyTasksTrackingids()
    {
        if(count($this->JoeyTrackingids) <= 0)
        {
            $task_ids = $this->GetCachedJoeyTasksIds();
            $this->JoeyTrackingids = Merchantids::whereIn('task_id',$task_ids)->pluck('tracking_id')->toArray();
        }

        return $this->JoeyTrackingids;

    }



    /**
     * Get FirstSortScan on current joey data.
     */
    public function FirstSortScan($return_type = 'created_at')
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('sort');

        $data =  SprintTaskHistory::whereIn('sprint__tasks_id',$joey_tasks_ids)
            ->where('status_id',$status_code)
            ->orderBy('date','asc')
            ->first(['*',\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto') as created_at")]);

        if($data != null) {
            // checking return type
            if ($return_type == 'created_at') {
                /**
                 * return only date time
                 */
                $data->toArray();
                return $data['created_at'];
            } elseif ($return_type == 'array') {
                /**
                 * return array of this db raw
                 */

                return $data->toArray();

            } elseif ($return_type == 'object') {
                /**
                 * return model object of this raw
                 */
                return $data;
            }

            /**
             * return empty string data not find
             */
            return '';
        }

    }




    /**
     * Get FirstPickUpScan current joey data.
     */
    public function FirstPickUpScan()
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        $data = self::whereIn('task_id',$joey_tasks_ids)->where('status',3)
            ->orderBy('created_at','asc')
            ->first(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at"));

        if($data != null) {
            return $data->created_at;
        }

        /**
         * return empty string data not find
         */
        return '';

    }




    /**
     * Get FirstPickUpScan current joey data by sprint tasks history table.
     */
    public function FirstPickUpScanBySprintTaskHistroy($return_type = 'created_at')
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('pickup');

        $data =  SprintTaskHistory::whereIn('sprint__tasks_id',$joey_tasks_ids)
            ->where('status_id',$status_code)
            ->orderBy('date','asc')
            ->first(['*',\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto') as created_at")]);

        if($data != null) {
            // checking return type
            if ($return_type == 'created_at') {
                /**
                 * return only date time
                 */
                $data->toArray();
                return $data['created_at'];
            } elseif ($return_type == 'array') {
                /**
                 * return array of this db raw
                 */

                return $data->toArray();

            } elseif ($return_type == 'object') {
                /**
                 * return model object of this raw
                 */
                return $data;
            }

            /**
             * return empty string data not find
             */
            return '';
        }

    }


    /**
     * Get  FirstDropScan current joey data.
     */
    public function FirstDropScan()
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        $data = self::whereIn('task_id',$joey_tasks_ids)->where('status',2)->orderBy('created_at','asc')->first(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at"));

        if($data != null) {
            return $data->created_at;
        }

        /**
         * return empty string data not find
         */
        return '';
    }

    /**
     * Get  FirstDropScan current joey data from sprint tasks history.
     */
    public function FirstDropScanBySprintTaskHistroy($return_type = 'created_at')
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('completed');

        $data =  SprintTaskHistory::whereIn('sprint__tasks_id',$joey_tasks_ids)
            ->whereIn('status_id',$status_code)
            ->orderBy('date','asc')
            ->first(['*',\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto') as created_at")]);

        if($data != null) {
            // checking return type
            if ($return_type == 'created_at') {
                /**
                 * return only date time
                 */
                $data->toArray();
                return $data['created_at'];
            } elseif ($return_type == 'array') {
                /**
                 * return array of this db raw
                 */

                return $data->toArray();

            } elseif ($return_type == 'object') {
                /**
                 * return model object of this raw
                 */
                return $data;
            }

            /**
             * return empty string data not find
             */
            return '';
        }

    }

    /**
     * Get  LastDropScan current joey data .
     */
    public function LastDropScan()
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        $data = self::whereIn('task_id',$joey_tasks_ids)->where('status',2)->orderBy('created_at','DESC')->first(\DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto') as created_at"));

        if($data != null) {
            return $data->created_at;
        }

        /**
         * return empty string data not find
         */
        return '';
    }

    /**
     * Get  LastDropScan current joey data from sprint tasks history.
     */
    public function LastDropScanBySprintTaskHistroy($return_type = 'created_at')
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('completed');

        $data =  SprintTaskHistory::whereIn('sprint__tasks_id',$joey_tasks_ids)
            ->whereIn('status_id',$status_code)
            ->orderBy('date','DESC')
            ->first(['*',\DB::raw("CONVERT_TZ(date,'UTC','America/Toronto') as created_at")]);

        if($data != null) {
            // checking return type
            if ($return_type == 'created_at') {
                /**
                 * return only date time
                 */
                $data->toArray();
                return $data['created_at'];
            } elseif ($return_type == 'array') {
                /**
                 * return array of this db raw
                 */

                return $data->toArray();

            } elseif ($return_type == 'object') {
                /**
                 * return model object of this raw
                 */
                return $data;
            }

            /**
             * return empty string data not find
             */
            return '';
        }

    }

    /**
     * Get Time Of TotalKM Scan Of Order in this Route .
     */
    public function TotalKM()  //calculate Assign Route Total KM
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        $data = JoeyRouteLocations::whereIn('task_id',$joey_tasks_ids)->sum('distance');
        return round( $data / 1000 ,2);
    }

    /**
     * Get Time Of TotalKM Scan Of Order in this Route of this joey .
     */
    public function ActualTotalKM()  //calculate ActualTotalKM Route Total KM
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('completed');

        $data = JoeyRouteLocations::join('sprint__tasks' , 'sprint__tasks.id', '=', 'joey_route_locations.task_id')
            ->join('sprint__sprints' , 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
            ->whereIn('sprint__sprints.status_id',$status_code)
            ->whereIn('joey_route_locations.task_id',$joey_tasks_ids)
            ->distinct('joey_route_locations.id')
            ->pluck('joey_route_locations.distance','sprint__sprints.id')->toArray();

        $data = round( array_sum($data) / 1000 , 2);
        return $data;
    }


    /**
     * Get Total Numbers Of Order Drops in this Route of this joey .
     */
    public function TotalOrderDropsCount()
    {
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // counting assigned task count
        return count($joey_tasks_ids);
    }

    /**
     * Get Total Numbers Of Orders Picked in this Route by this joey .
     */
    public function TotalOrderPickedCount()
    {
        // gating current routs tasks ids by this joey
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('pickup');

        return SprintTaskHistory::whereIn('sprint__tasks_id',$joey_tasks_ids)
            ->where('status_id',$status_code)
            ->distinct('sprint__tasks_id')
            ->count();
    }


    /**
     * Get Total Numbers Of Orders Completed in this Route by this joey .
     */
    public function TotalOrderDropsCompletedCount()
    {
        // gating current routs tasks ids by this joey
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('completed');

        return SprintTasks::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__sprints.status_id',$status_code)
            ->whereIn('sprint__tasks.id', $joey_tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->distinct('sprint__sprints.id')
            ->count();
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route this joey.
     */
    public function TotalOrderReturnCount()
    {
        // gating current routs tasks ids by this joey
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('return');

        return SprintTasks::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__sprints.status_id',$status_code)
            ->whereIn('sprint__tasks.id', $joey_tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->distinct('sprint__sprints.id')
            ->count();
    }


    /**
     * Get Total Numbers Of Orders Unattempted in this Route this joey.
     */
    public function TotalOrderUnattemptedCount()
    {
        //return $this->TotalOrderPickedCount() - ($this->TotalOrderDropsCompletedCount() + $this->TotalOrderReturnCount());
        return $this->TotalOrderDropsCount() - ($this->TotalOrderDropsCompletedCount() + $this->TotalOrderReturnCount());
    }


    /**
     * Get Total Numbers Of Orders Not Scan in this Route this joey.
     */
    public function TotalOrderNotScanCount()
    {

        // gating current routs tasks ids by this joey
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting fisrt sort scan status codes
        $status_code = getStatusCodes('unattempted');

        return SprintTasks::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__sprints.status_id',$status_code)
            ->whereIn('sprint__tasks.id', $joey_tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->distinct('sprint__sprints.id')
            ->count();
    }


    /**
     * Get the Location data.
     */
    /**
     * Get Location.
     */

    public function locations()
    {
        // gating current task location
        $task = (isset($this->SprintTask))?$this->SprintTask:null;


        if($task == null)
        {
            return 'task is null';
        }
        elseif($task->location == null)
        {
            return 'location is null';
        }
        return $task->location;

    }

    /**
     * Get Vendor.
     */

    public function Vendor()
    {

        // gating current task vendor
        $task = $this->SprintTask;

        if($task == null)
        {
            return 'task is null';
        }
        elseif ($task->Sprints == null) {
            return 'Sprint is null';
        } elseif ($task->Sprints->Vendor == null) {
            return 'vendor is null';
        }
        return $task->Sprints->Vendor;

    }

    // getting current joey work on big box route
    function CustomRoutingTrackingId($return_type = 'get',$orderBy ='DESC' , $data_type ='object')
    {

        $tracking_ids = $this->GetJoeyTasksTrackingids();
        $query = CustomerRoutingTrackingId::whereIn('tracking_id',$tracking_ids)->orderBy('id',$orderBy);
        $data = ($data_type == 'first')?$query->first():$query->get();

        // checking the data is not null
        if($data != null && count($data) > 0)
        {
            if($data_type =='object')
            {
                return $data;
            }
            elseif($data_type =='array')
            {
                return $data->toArray();
            }
        }

        return $data;

    }


    // checking this route use big box
    function IsthisRouteUseBigBox()
    {
        $tracking_ids = $this->GetJoeyTasksTrackingids();
        $data = CustomerRoutingTrackingId::whereIn('tracking_id',$tracking_ids)
            ->where('is_big_box',1)
            ->first();

        if($data == null)
        {
            return 0;
        }
        return  $data->is_big_box;
    }


    /**
     * get flag orders data by sprint and joey id
     */

    public function FlagDataByJoey()
    {

        // checking the flag order data is already loaded
        if($this->FlagDataByJoey != null)
        {
            return $this->FlagDataByJoey;
        }

        // gating current routs tasks ids by this joey
        $joey_tasks_ids =  $this->JoeyTasksIds();

        // getting this route sprint ids
        $sprints_ids = Task::join('sprint__sprints','sprint__tasks.sprint_id', '=', 'sprint__sprints.id')
            ->whereIn('sprint__tasks.id', $joey_tasks_ids)
            ->where('sprint__sprints.deleted_at', null)
            ->orderBy('sprint__sprints.id',"ASC")
            ->distinct('sprint__sprints.id')
            ->select('sprint__sprints.id')
            ->pluck('sprint__sprints.id')
            ->toArray();

        // getting joey performance data
        $data =  JoeyPerformanceHistory::where('joey_id',$this->joey_id)
            ->FilterUnFlagged()
            ->TypeOrder()
            ->whereIn('sprint_id',$sprints_ids)
            ->orderBy('id','DESC')
            ->get();

        $this->FlagDataByJoey = $data;

        // check data empty
        if($data->isEmpty())
        {
            return null;
        }

        return $this->FlagDataByJoey;

    }


    /**
     * get flag orders data by route
     */

    public function FlagDataByRoute()
    {

        return $this->hasOne(JoeyPerformanceHistory::class,'route_id','route_id')->TypeRoute();

    }

    private function PayoutQueryCalculatoinByJoey()
    {



        // checking the calculation is already done
        if(!$this->IsCalculationDoneByJoey)
        {
            // getting current date search
            $self_date =$this->updated_at->timezone('America/Toronto')->toDateString();
            $start_data = (!empty($this->DateRangeSelected['start_date'])) ? $this->DateRangeSelected['start_date'] : $self_date.' 00:00:00' ;
            $end_date = (!empty($this->DateRangeSelected['end_date'])) ? $this->DateRangeSelected['end_date'] : $self_date.' 23:59:59' ;
            $requestRouteId = (!empty($this->request_route_id)) ? $this->request_route_id : 0 ;

            $sorted_data = [
                "pickup_sort"=> [],
                "completed_and_return"=> [],
                "completed_drops_task_ids" => [],
                "first_pickup_time"=> '',
                "first_pickup_time_capture"=> false,
                "first_drop_time"=> '',
                "first_drop_time_capture"=> false,
                "last_drop_time"=> '',
                "actual_total_km"=> 0,
            ];


            if ($requestRouteId > 0)
            {
                // getting data for calculation
                $query = self::has('JoeyRouteLocation')
                    ->where('joey_id', $this->joey_id)
                    ->where('route_id',$this->route_id)
                    ->whereIn('status',[$this->Statuses['completed'],$this->Statuses['pickup'],$this->Statuses['return']])
                    ->orderBy('id','asc')
                    ->get();
            }
            else
            {
                // getting data for calculation
                $query = self::has('JoeyRouteLocation')
                    ->where('joey_id', $this->joey_id)
                    ->where('route_id',$this->route_id)
                    //->whereBetween(\DB::raw("CONVERT_TZ(route_history.created_at,'UTC','America/Toronto')"),[$start_data,$end_date])
                    ->whereBetween('created_at',[$start_data,$end_date])
                    ->whereIn('status',[$this->Statuses['completed'],$this->Statuses['pickup'],$this->Statuses['return']])
                    ->orderBy('id','asc')
                    ->get();
            }


            // now sorting data
            foreach($query as $single_data)
            {
                // checking the status is pickup
                if($single_data->status == $this->Statuses['pickup'])
                {
                    // adding the records of pickup
                    $sorted_data['pickup_sort'][$single_data->task_id] = $single_data->task_id;
                    // now capturing first pickup time
                    if(!$sorted_data['first_pickup_time_capture'] && !is_null($single_data->created_at))
                    {
                        $sorted_data['first_pickup_time'] =  ConvertTimeZone($single_data->created_at,'UTC','America/Toronto');
                        $sorted_data['first_pickup_time_capture'] = true;
                    }
                    else
                    {
                        $sorted_data['first_pickup_time_capture'] = true;
                    }

                }
                else
                {
                    // now capturing first drop time
                    if(!$sorted_data['first_drop_time_capture'] && $single_data->status == $this->Statuses['completed'] && !is_null($single_data->created_at))
                    {
                        $sorted_data['first_drop_time'] = ConvertTimeZone($single_data->created_at,'UTC','America/Toronto');
                        $sorted_data['first_drop_time_capture'] = true;

                    }
                    elseif($single_data->status == $this->Statuses['completed'])
                    {
                        $sorted_data['first_drop_time_capture'] = true;
                    }

                    // now capturing last drop time
                    $sorted_data['last_drop_time'] = ($single_data->status == $this->Statuses['completed'] && !is_null($single_data->created_at)) ? ConvertTimeZone($single_data->created_at,'UTC','America/Toronto') : $sorted_data['last_drop_time'];

                    // data of completed drops and returns
                    $sorted_data['completed_and_return'][$single_data->task_id] = $single_data->status;
                }

            }

            // getting count values
            $count_values =  array_count_values($sorted_data['completed_and_return']);

            if(isset($count_values[$this->Statuses['completed']]))
            {
                // getting all completed orders task ids
                foreach ($sorted_data['completed_and_return'] as $index => $data) {
                    if ($data == $this->Statuses['completed']) {
                        $sorted_data['completed_drops_task_ids'][$index . '-' . $data] = $index;
                    }
                }


                // calculating actual km
                $actual_km = JoeyRouteLocations::whereIn('task_id', $sorted_data['completed_drops_task_ids'])->sum('distance');
                $sorted_data['actual_total_km'] = round($actual_km / 1000, 2);
            }


            // updating values of counts
            $this->JoeyPickUpOrders =  count($sorted_data['pickup_sort']);
            $this->JoeyCompletedDrops = (isset($count_values[$this->Statuses['completed']])) ?  $count_values[$this->Statuses['completed']]:0;
            $this->JoeyReturnDrops = (isset($count_values[$this->Statuses['return']])) ?  $count_values[$this->Statuses['return']]:0;
            $this->JoeyFirstPickupTime = $sorted_data['first_pickup_time'];
            $this->JoeyFirstDropTime = $sorted_data['first_drop_time'];
            $this->JoeyLastDropTime = $sorted_data['last_drop_time'];
            $this->JoeyActualTotalKM = $sorted_data['actual_total_km'];
            $this->IsCalculationDoneByJoey = true;

        }

    }


    /**
     * Get Total Numbers Of Orders Completed in this Route by this joey .
     */
    public function JoeyTotalOrderDropsCompletedCount()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyCompletedDrops;
    }

    /**
     * Get Total Numbers Of Orders Picked in this Route by this joey .
     */
    public function JoeyTotalOrderPickedCount()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyPickUpOrders;
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route this joey.
     */
    public function JoeyTotalOrderReturnCount()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyReturnDrops;
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route this joey.
     */
    public function JoeyTotalOrderUnattemptedCount()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->TotalOrderDropsCount() - ($this->JoeyCompletedDrops + $this->JoeyReturnDrops);
    }


    /**
     * Get FirstPickUpScan current joey data.
     */
    public function JoeyFirstPickUpScan()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyFirstPickupTime;
    }

    /**
     * Get  FirstDropScan current joey data.
     */
    public function JoeyFirstDropScan()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyFirstDropTime;
    }

    /**
     * Get  LastDropScan current joey data .
     */
    public function JoeyLastDropScan()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyLastDropTime;
    }


    /**
     * Get Time Of TotalKM Scan Of Order in this Route of this joey .
     */
    public function JoeyActualTotalKM()  //calculate ActualTotalKM Route Total KM
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey();
        return $this->JoeyActualTotalKM;
    }


    // testing functions

    private function PayoutQueryCalculatoinByJoey_test()
    {
        // checking the calculation is already done
        if(!$this->IsCalculationDoneByJoey)
        {

            // getting current date search
            $self_date = $this->updated_at->timezone('America/Toronto')->toDateString();
            $start_data = (!empty($this->DateRangeSelected['start_date'])) ? $this->DateRangeSelected['start_date'] : $self_date.' 00:00:00' ;
            $end_date = (!empty($this->DateRangeSelected['end_date'])) ? $this->DateRangeSelected['end_date'] : $self_date.' 23:59:59' ;
            $requestRouteId = (!empty($this->request_route_id)) ? $this->request_route_id : 0 ;

            $sorted_data = [
                "pickup_sort"=> [],
                "completed_and_return"=> [],
                "completed_drops_task_ids" => [],
                "first_pickup_time"=> '',
                "first_pickup_time_capture"=> false,
                "last_pickup_time"=> '',
                "first_drop_time"=> '',
                "first_drop_time_capture"=> false,
                "last_drop_time"=> '',
                "actual_total_km"=> 0,
            ];

            if ($requestRouteId > 0)
            {
                // getting data for calculation
                $query = self::has('JoeyRouteLocation')
                    ->where('joey_id', $this->joey_id)
                    ->where('route_id',$this->route_id)
                    ->whereIn('status',[$this->Statuses['completed'],$this->Statuses['pickup'],$this->Statuses['return']])
                    ->orderBy('id','asc')
                    ->get();
            }
            else
            {
                // getting data for calculation
                $query = self::has('JoeyRouteLocation')
                    ->where('joey_id', $this->joey_id)
                    ->where('route_id',$this->route_id)
                    //->whereBetween(\DB::raw("CONVERT_TZ(route_history.created_at,'UTC','America/Toronto')"),[$start_data,$end_date])
                    ->whereBetween('created_at',[$start_data,$end_date])
                    ->whereIn('status',[$this->Statuses['completed'],$this->Statuses['pickup'],$this->Statuses['return']])
                    ->orderBy('id','asc')
                    ->get();
            }
            // now sorting data
            foreach($query as $single_data)
            {
                // checking the status is pickup
                if($single_data->status == $this->Statuses['pickup'])
                {
                    // adding the records of pickup
                    $sorted_data['pickup_sort'][$single_data->task_id] = $single_data->task_id;
                    // now capturing first pickup time
                    if(!$sorted_data['first_pickup_time_capture'] && !is_null($single_data->created_at))
                    {
                        $sorted_data['first_pickup_time'] =  ConvertTimeZone($single_data->created_at,'UTC','America/Toronto');
                        $sorted_data['first_pickup_time_capture'] = true;
                    }
                    else
                    {
                        $sorted_data['first_pickup_time_capture'] = true;
                    }

                    // last pickup
                    //$sorted_data['last_pickup_time'] = (!is_null($single_data->created_at)) ? ConvertTimeZone($single_data->created_at,'UTC','America/Toronto') : $sorted_data['last_pickup_time'];
                }
                else
                {
                    // now capturing first drop time
                    if(!$sorted_data['first_drop_time_capture'] && $single_data->status == $this->Statuses['completed'] && !is_null($single_data->created_at))
                    {
                        $sorted_data['first_drop_time'] = ConvertTimeZone($single_data->created_at,'UTC','America/Toronto');
                        $sorted_data['first_drop_time_capture'] = true;

                    }
                    elseif($single_data->status == $this->Statuses['completed'])
                    {
                        $sorted_data['first_drop_time_capture'] = true;
                    }

                    // now capturing last drop time
                    $sorted_data['last_drop_time'] = ($single_data->status == $this->Statuses['completed'] && !is_null($single_data->created_at)) ? ConvertTimeZone($single_data->created_at,'UTC','America/Toronto') : $sorted_data['last_drop_time'];

                    // data of completed drops and returns
                    $sorted_data['completed_and_return'][$single_data->task_id] = $single_data->status;
                }

            }

            // getting count values
            $count_values =  array_count_values($sorted_data['completed_and_return']);

            if(isset($count_values[$this->Statuses['completed']]))
            {
                // getting all completed orders task ids
                foreach ($sorted_data['completed_and_return'] as $index => $data) {
                    if ($data == $this->Statuses['completed']) {
                        $sorted_data['completed_drops_task_ids'][$index . '-' . $data] = $index;
                    }
                }


                // calculating actual km
                $actual_km = JoeyRouteLocations::whereIn('task_id', $sorted_data['completed_drops_task_ids'])->sum('distance');
                $sorted_data['actual_total_km'] = round($actual_km / 1000, 2);

            }


            // updating values of counts
            $this->JoeyPickUpOrders =  count($sorted_data['pickup_sort']);
            $this->JoeyCompletedDrops = (isset($count_values[$this->Statuses['completed']])) ?  count($sorted_data['completed_drops_task_ids']):0;
            $this->JoeyReturnDrops = (isset($count_values[$this->Statuses['return']])) ?  $count_values[$this->Statuses['return']]:0;
            $this->JoeyFirstPickupTime = $sorted_data['first_pickup_time'];
            $this->JoeyFirstDropTime = $sorted_data['first_drop_time'];
            $this->JoeyLastDropTime = $sorted_data['last_drop_time'];
            $this->JoeyActualTotalKM = $sorted_data['actual_total_km'];
            $this->IsCalculationDoneByJoey = true;

        }

    }


    /**
     * Get Total Numbers Of Orders Completed in this Route by this joey .
     */
    public function JoeyTotalOrderDropsCompletedCount_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyCompletedDrops;
    }

    /**
     * Get Total Numbers Of Orders Picked in this Route by this joey .
     */
    public function JoeyTotalOrderPickedCount_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyPickUpOrders;
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route this joey.
     */
    public function JoeyTotalOrderReturnCount_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyReturnDrops;
    }

    /**
     * Get Total Numbers Of Orders Unattempted in this Route this joey.
     */
    public function JoeyTotalOrderUnattemptedCount_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->TotalOrderDropsCount() - ($this->JoeyCompletedDrops + $this->JoeyReturnDrops);
    }


    /**
     * Get FirstPickUpScan current joey data.
     */
    public function JoeyFirstPickUpScan_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyFirstPickupTime;
    }

    /**
     * Get  FirstDropScan current joey data.
     */
    public function JoeyFirstDropScan_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyFirstDropTime;
    }

    /**
     * Get  LastDropScan current joey data .
     */
    public function JoeyLastDropScan_test()
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyLastDropTime;
    }


    /**
     * Get Time Of TotalKM Scan Of Order in this Route of this joey .
     */
    public function JoeyActualTotalKM_test()  //calculate ActualTotalKM Route Total KM
    {
        // trigger
        $this->PayoutQueryCalculatoinByJoey_test();
        return $this->JoeyActualTotalKM;
    }

    public function PayoutQueryCalculatoinByJoeyForLockingPayout()
    {
        $sorted_data = [
            "pickup_sort"=> [],
            "completed_and_return"=> [],
            "completed_drops_task_ids" => [],
            "first_pickup_time"=> '',
            "first_pickup_time_capture"=> false,
            "last_pickup_time"=> '',
            "first_drop_time"=> '',
            "first_drop_time_capture"=> false,
            "last_drop_time"=> '',
            "actual_total_km"=> 0,
        ];

        //dd($this->updated_at->toDateString());
        // getting data for calculation
        $query = self::has('JoeyRouteLocation')
            ->where('joey_id', $this->joey_id)
            ->where('route_id',$this->route_id)
            ->whereIn('status',[$this->Statuses['completed'],$this->Statuses['pickup'],$this->Statuses['return']])
            ->orderBy('id','asc')
            ->get();

        // now sorting data
        foreach($query as $single_data)
        {
            // checking the status is pickup
            if($single_data->status == $this->Statuses['pickup'])
            {
                // adding the records of pickup
                $sorted_data['pickup_sort'][$single_data->task_id] = $single_data->task_id;
                // now capturing first pickup time
                if(!$sorted_data['first_pickup_time_capture'] && !is_null($single_data->created_at))
                {
                    $sorted_data['first_pickup_time'] =  ConvertTimeZone($single_data->created_at,'UTC','America/Toronto');
                    $sorted_data['first_pickup_time_capture'] = true;
                }
                else
                {
                    $sorted_data['first_pickup_time_capture'] = true;
                }

                // last pickup
                //$sorted_data['last_pickup_time'] = (!is_null($single_data->created_at)) ? ConvertTimeZone($single_data->created_at,'UTC','America/Toronto') : $sorted_data['last_pickup_time'];
            }
            else
            {
                // now capturing first drop time
                if(!$sorted_data['first_drop_time_capture'] && $single_data->status == $this->Statuses['completed'] && !is_null($single_data->created_at))
                {
                    $sorted_data['first_drop_time'] = ConvertTimeZone($single_data->created_at,'UTC','America/Toronto');
                    $sorted_data['first_drop_time_capture'] = true;

                }
                elseif($single_data->status == $this->Statuses['completed'])
                {
                    $sorted_data['first_drop_time_capture'] = true;
                }

                // now capturing last drop time
                $sorted_data['last_drop_time'] = ($single_data->status == $this->Statuses['completed'] && !is_null($single_data->created_at)) ? ConvertTimeZone($single_data->created_at,'UTC','America/Toronto') : $sorted_data['last_drop_time'];

                // data of completed drops and returns
                $sorted_data['completed_and_return'][$single_data->task_id] = $single_data->status;
            }

        }

        // getting count values
        $count_values =  array_count_values($sorted_data['completed_and_return']);

        if(isset($count_values[$this->Statuses['completed']]))
        {
            // getting all completed orders task ids
            foreach ($sorted_data['completed_and_return'] as $index => $data) {
                if ($data == $this->Statuses['completed']) {
                    $sorted_data['completed_drops_task_ids'][$index . '-' . $data] = $index;
                }
            }


            // calculating actual km
            $actual_km = JoeyRouteLocations::whereIn('task_id', $sorted_data['completed_drops_task_ids'])->sum('distance');
            $sorted_data['actual_total_km'] = round($actual_km / 1000, 2);

        }


        // updating values of counts
        $JoeyPickUpOrders =  count($sorted_data['pickup_sort']);
        $JoeyCompletedDrops = (isset($count_values[$this->Statuses['completed']])) ?  count($sorted_data['completed_drops_task_ids']):0;
        $JoeyReturnDrops = (isset($count_values[$this->Statuses['return']])) ?  $count_values[$this->Statuses['return']]:0;
        $JoeyFirstPickupTime = $sorted_data['first_pickup_time'];
        $JoeyFirstDropTime = $sorted_data['first_drop_time'];
        $JoeyLastDropTime = $sorted_data['last_drop_time'];
        $JoeyActualTotalKM = $sorted_data['actual_total_km'];
        // returning data
        return [
            "JoeyPickUpOrders" =>$JoeyPickUpOrders,
            "JoeyCompletedDrops" => $JoeyCompletedDrops,
            "JoeyReturnDrops" => $JoeyReturnDrops,
            "JoeyFirstPickupTime" =>$JoeyFirstPickupTime,
            "JoeyFirstDropTime" =>$JoeyFirstDropTime,
            "JoeyLastDropTime" =>$JoeyLastDropTime,
            "JoeyActualTotalKM" =>$JoeyActualTotalKM,
        ];
    }

    /**
     * Get Payout Summary Note.
     */
    public function PayoutSummaryNote()
    {
        return $this->belongsTo(PayoutSummary::class,'route_id', 'id');
    }

}
