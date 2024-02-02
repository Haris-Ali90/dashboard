<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class JoeyRouteLocations extends Model
{
    use SoftDeletes;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'joey_route_locations';
    private $routific_estimated_total_time_responce = [
        "total_time"=>'00:00:00',
        "start_time"=>'00:00:00',
        "end_time"=>'00:00:00',
        "is_updated"=>false
    ];
 
    protected $guarded = [
    ];

    protected $fillable = [
        "id",
        "route_id",
        "ordinal",
        "task_id",
        "arrival_time",
        "finish_time",
        "distance",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get joey routs sprint task history.
     */
    public function SprintTaskHistory()
    {
        return $this->hasMany( SprintTaskHistory::class,'sprint__tasks_id', 'task_id');
    }

    public function TotalOrderDropsCompletedCount()
    {
        // gating current routs tasks ids
        $tasks_ids = $this->GetAllTaskIds();
        return SprintTaskHistory::whereIn('sprint__tasks_id',$tasks_ids)->where('status_id',17)->count();
    }

    /**
     * Scope a query to only not deleted records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted_at', null);
    }
	
	    public function joeyRoute()
    {
        return $this->belongsTo(JoeyRoutes::class,'route_id','id')->whereNull('deleted_at');
    }

    public function taskSprintConfirmation()
    {
        return $this->belongsTo(SprintConfirmation::class,'task_id','task_id')
            ->whereNotNull('attachment_path')->orderBy('id','desc')->select('attachment_path');
    }

    public function routeHistory()
    {
        return $this->hasMany(RouteHistory::class,'route_location_id','id')->whereNull('deleted_at')->whereNotNull('joey_id')->orderBy('created_at','desc');
    }
	
	public function SprintTask()
    {
        return $this->belongsTo(Task::class,'task_id','id')->whereNull('deleted_at');
    }
    public static function maps($route_id)
    {
        return JoeyRouteLocations::join('sprint__tasks','task_id','=','sprint__tasks.id')
            ->join('locations','location_id','=','locations.id')
            ->where('route_id','=',$route_id)
            ->whereNull('joey_route_locations.deleted_at')
            ->whereNotIn('status_id',[38,36,17,112,113,114,116,117,118,132,136,138,139,143,144,104,105,106,107,108,109,110,111,131,135])
            ->orderBy('joey_route_locations.ordinal')
            ->get(['type','route_id','joey_route_locations.ordinal','sprint_id','address','postal_code','latitude','longitude']);

    }

    public function SelfDataByRouteID()
    {
        return $this->hasMany( self::class,'route_id', 'route_id')->whereNull('deleted_at');
    }

    public function GetRoutificEstimatedTotalTimeTest($force_recalculate = false)
    {
        $routific_estimated_total_time_responce = $this->routific_estimated_total_time_responce;

        // the difference is already calculated
        if($force_recalculate == false && $routific_estimated_total_time_responce['is_updated'] == true)
        {
            //return $routific_estimated_total_time_responce;
        }
        $data  = $this->SelfDataByRouteID->sortBy('ordinal')->toArray();
        // checking the data exist
        $start_time = '';
        $end_time = '';
        $array_count = count($data);
        $last_index = $array_count - 1;
        $current_data = date('Y-m-d');

        $diff_time = '00:00:00';
        if($array_count > 0)
        {
            $start_time =   $data[0]['arrival_time'];
            $end_time = $data[$last_index]['finish_time'];

            if ($start_time > '23:59')
            {
                $start_time = '00:00';
            }
            if ($end_time > '23:59')
            {
                $end_time = '00:00';
            }
            $diff_time = DifferenceTwoDataTime(
                $current_data.' '.$start_time.":00",
                $current_data.' '.$end_time.":00"
            );

            $this->routific_estimated_total_time_responce['total_time'] = $diff_time;
            $this->routific_estimated_total_time_responce['start_time'] = $start_time;
            $this->routific_estimated_total_time_responce['end_time'] = $end_time;
        }


        // updating data
        $this->routific_estimated_total_time_responce['is_updated']  = true;
        return $this->routific_estimated_total_time_responce;

    }
}