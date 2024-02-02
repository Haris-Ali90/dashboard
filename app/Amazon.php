<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Amazon extends Authenticatable
{
    protected $table = 'amazon_dashboard';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'order_id','route','joey','address','scheduled_duetime','arrival_time','departure_time','picked_hub_time','sorter_time','start_time','end_time','dropoff_eta','delivery_time','tracking_id','signature','sprint_id','sprint_status','task_status','merchant_order_num','image','vendor_id'
    ];

    public static function montrealDashboardCounts($today_date)
    {
        $date = date('Y-m-d', strtotime($today_date. ' -1 days'));

        /*$counts = Sprint::join('sprint__tasks_history','sprint__tasks_history.sprint_id','=','sprint__sprints.id')
            ->where(DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['sprint__sprints.creator_id' => 477260])
            ->whereNull('sprint__sprints.deleted_at')
            ->select([DB::raw('COUNT(DISTINCT(sprint_id)) AS total_orders'),
                DB::raw('COUNT(DISTINCT(CASE WHEN sprint__tasks_history.status_id = 121 THEN sprint_id ELSE NULL END)) AS pickup_orders'),
                DB::raw('COUNT(DISTINCT(CASE WHEN sprint__tasks_history.status_id = 133 THEN sprint_id ELSE NULL END)) AS sorted_orders'),
                DB::raw('COUNT(DISTINCT(CASE WHEN sprint__tasks_history.status_id  IN (17,113,114,116,117,118,138,139) THEN sprint_id ELSE NULL END)) AS delivered_orders')])->get();
        $counts[0]['notscan'] = Sprint::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['creator_id' => 477260])
            ->whereNull('sprint__sprints.deleted_at')
            ->whereIn('status_id', [61,13])
            ->count();
        $counts[0]['mainfest_orders'] = DB::table('mainfest_fields')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")->whereNotNull('trackingID')
            ->whereNull('deleted_at')->where(['vendor_id' => 477260])->count();
        $counts[0]['failed_orders'] = DB::table('xml_failed_orders')->join('mainfest_fields','mainfest_fields.trackingID','=','xml_failed_orders.tracking_id')
            ->where(DB::raw("CONVERT_TZ(xml_failed_orders.created_at,'UTC','America/Toronto')"),'like',$date."%")->whereNotNull('mainfest_fields.trackingID')
            ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477260])->count();
        $counts[0]['return_orders'] = DB::table('sprint__sprints')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")->whereIn('status_id',[106,107,108,109,110])
            ->whereNull('deleted_at')->where(['creator_id' => 477260])->count();

        $query="SELECT 
            COUNT(DISTINCT(sprint_id)) AS total_orders,	
            COUNT(DISTINCT(CASE WHEN sprint__tasks_history.`status_id`=121 THEN sprint_id ELSE NULL END)) AS pickup_orders,
            COUNT(DISTINCT(CASE WHEN sprint__tasks_history.`status_id`=133 THEN sprint_id ELSE NULL END)) AS sorted_orders,
            COUNT(DISTINCT(CASE WHEN sprint__tasks_history.`status_id` IN(17,113,114,116,117,118,138,139) THEN sprint_id ELSE NULL END)) AS delivered_orders
            FROM sprint__sprints
            JOIN sprint__tasks_history ON(sprint_id=sprint__sprints.id) 
            WHERE creator_id IN (477260)
            AND sprint__sprints.deleted_at IS NULL 
            AND CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') LIKE '".$date."%'";
        $counts= DB::select($query);*/
		
		       $sprintIds = Sprint::where(DB::raw("CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto')"),'like',$date."%")
            ->where(['sprint__sprints.creator_id' => 477260])
            ->whereNull('sprint__sprints.deleted_at')->pluck('id')->toArray();
        $history = new SprintTaskHistory();
        $counts = $history->getCounts($sprintIds);

        /*$query="SELECT
            COUNT(DISTINCT(sprint_id)) AS total_orders,	
            COUNT(DISTINCT(CASE WHEN sprint__tasks_history.`status_id`=121 THEN sprint_id ELSE NULL END)) AS pickup_orders,
            COUNT(DISTINCT(CASE WHEN sprint__tasks_history.`status_id`=133 THEN sprint_id ELSE NULL END)) AS sorted_orders,
            COUNT(DISTINCT(CASE WHEN sprint__tasks_history.`status_id` IN(17,113,114,116,117,118,138,139) THEN sprint_id ELSE NULL END)) AS delivered_orders
            FROM sprint__tasks_history
            WHERE sprint_id IN (".$sprintIds.")";
        $counts= DB::select($query);*/

        $counts['notscan'] = 0;//Sprint::where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")
            //->where(['creator_id' => 477260])
           // ->whereNull('sprint__sprints.deleted_at')
           // ->whereIn('status_id', [61,13])
           // ->count();

        $counts['mainfest_orders'] = DB::table('mainfest_fields')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")->whereNotNull('trackingID')
            ->whereNull('deleted_at')->where(['vendor_id' => 477260])->count();
        $counts['failed_orders'] = DB::table('xml_failed_orders')->join('mainfest_fields','mainfest_fields.trackingID','=','xml_failed_orders.tracking_id')
            ->where(DB::raw("CONVERT_TZ(xml_failed_orders.created_at,'UTC','America/Toronto')"),'like',$date."%")->whereNotNull('mainfest_fields.trackingID')
            ->whereNull('mainfest_fields.deleted_at')->where(['vendor_id' => 477260])->count();
        $counts['return_orders'] = 0;//DB::table('sprint__sprints')->where(DB::raw("CONVERT_TZ(created_at,'UTC','America/Toronto')"),'like',$date."%")->whereIn('status_id',[106,107,108,109,110])
            //->whereNull('deleted_at')->where(['creator_id' => 477260])->count();

        return $counts;


    }

}
