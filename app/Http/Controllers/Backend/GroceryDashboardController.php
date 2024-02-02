<?php

namespace App\Http\Controllers\Backend;

use App\Sprint;
use App\Task;
use App\Walmart;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use DateTime;
use DateTimeZone;
class GroceryDashboardController extends BackendController
{

    /**
     * Get Grocery
     */
    public function statistics_grocery_index(Request $request)
    {
        $date=$request->get('date');
       if(empty($date))
       {
           $date=date("Y-m-d");
       }
        return backend_view('groceryDashboard.statistics_grocery_dashboard',compact('date'));
    }

    /**
     * Get Grocery OTD Graph
     */
    public function ajax_render_otd_charts(Request $request)
    {
        $date=$request->get('date');

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');

        $query="select a.sprint_id,concat(a.sprint_id,'-',a.ordinal-1) as order_id,
(select attachment_path from sprint__confirmations where task_id=a.task_id and title='Upload Image' and attachment_path IS NOT NULL limit 1) as image,
(select attachment_path from sprint__confirmations where task_id=a.task_id and title='Signature' and attachment_path IS NOT NULL limit 1) as signature,
concat(first_name,' ',last_name) as joey_name,dropoff_eta,status_id,locations.address,merchantids.merchant_order_num,end_time,
(select CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  as arrival_time from sprint__tasks_history where sprint__tasks_history.status_id=67 and sprint__tasks_history.sprint_id=a.sprint_id limit 1) as arrival_time,
(select CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  as departure_time from sprint__tasks_history where sprint__tasks_history.status_id=15 and sprint__tasks_history.sprint_id=a.sprint_id limit 1) as departure_time,
(select CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  as delivery_time from sprint__tasks_history where sprint__tasks_history.status_id=17 and sprint__tasks_id=a.task_id limit 1) as delivery_time,
(select CONVERT_TZ(from_unixtime(eta_time),'UTC','America/Toronto') from sprint__tasks where type='pickup' and sprint_id=a.sprint_id) as arrival_eta 
from
(select sprint__tasks.id as task_id,sprint__tasks.sprint_id,CONVERT_TZ(from_unixtime(due_time),'UTC','America/Toronto') as dropoff_eta,location_id,ordinal from sprint__tasks where type='dropoff' and sprint__tasks.deleted_at IS NULL) as a 
join sprint__sprints on(a.sprint_id=sprint__sprints.id)
join joeys on(joeys.id=joey_id)
join locations on (a.location_id=locations.id) 
left join merchantids on(merchantids.task_id=a.task_id)
where between a.dropoff_eta '".$start."' and a.dropoff_eta '".$end."%' and creator_id=477164 and sprint__sprints.status_id!=36 order by sprint_id,ordinal";
        $fullrecord=DB::select($query);
        $total=0;
        $otd=0;
        if(!empty($fullrecord)){
            foreach($fullrecord as $record) {

                if($record->end_time!=NULL){
                    $record->end_time = (new \DateTime($record->end_time))->setTimezone(new \DateTimeZone('America/Toronto'))->format('H:i:s');
                    if(date('H:i:s',strtotime($record->end_time)+900) > date('H:i:s',strtotime($record->delivery_time))){
                        $otd++;
                    }  
                }
                else{
                    $otd++;
                }
                $total++;

            }
            if($total==0){
                $total=1;
            }
        }

         $data_set_one = ['y1'=>0,'y2'=>100,"tag1"=>"On Time Deliveries","tag2"=>"Off Time Deliveries"];
        if($otd==0)
        {
            return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$data_set_one]));
        }

        if($record->end_time!=NULL) {
            $data_set_one = ['y1' => round((($otd) / $total) * 100, 0), 'y2' => 100 - round((($otd) / $total) * 100, 0), "tag1" => "On Time Deliveries", "tag2" => "Off Time Deliveries"];
        }
       
       
        return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$data_set_one]));
    }

    /**
     * Get Grocery Orders
     */
    public function ajax_render_grocery_orders(Request $request)
    {
       
        $date= ($request->date != null) ? $request->date : date("Y-m-d");
         $page= (empty($request->page)) ? 1 : $request->page;
        // $Records_Per_page= 10;
        // $offset_value = ($page-1) * $Records_Per_page;

        $start_dt = new DateTime($date." 00:00:00", new DateTimezone('America/Toronto'));
        $start_dt->setTimeZone(new DateTimezone('UTC'));
        $start = $start_dt->format('Y-m-d H:i:s');

        $end_dt = new DateTime($date." 23:59:59", new DateTimezone('America/Toronto'));
        $end_dt->setTimeZone(new DateTimezone('UTC'));
        $end = $end_dt->format('Y-m-d H:i:s');


        $fullrecord=Task::join('sprint__sprints','sprint__sprints.id','=','sprint__tasks.sprint_id')
        ->whereIn('sprint__sprints.creator_id', [477164,477484])
            ->where(\DB::raw("from_unixtime(sprint__tasks.due_time)"),'>',$start)->where(\DB::raw("from_unixtime(sprint__tasks.due_time)"),'<',$end)
            //->where(\DB::raw("CONVERT_TZ(from_unixtime(sprint__tasks.due_time),'UTC','America/Toronto')"), 'like', $date . "%")
            ->where('sprint__sprints.deleted_at', null)->whereNotIn('sprint__sprints.status_id',[38])
            ->where('sprint__tasks.deleted_at', null)->where('sprint__tasks.type', 'dropoff')
            ->orderBy('sprint__sprints.id', 'asc')->orderBy('sprint__tasks.ordinal', 'asc')
            ->get([\DB::raw("concat(sprint__sprints.id,'-',sprint__tasks.ordinal-1) as order_id"),'sprint__tasks.id','joey_id','sprint__sprints.id as sprint_id','sprint__sprints.status_id' ,\DB::raw("CONVERT_TZ(from_unixtime(due_time),'UTC','America/Toronto') as dropoff_eta"),'sprint__tasks.location_id','sprint__tasks.ordinal']);
			
        $totalorder=0;
        $otd=0;
        if(!empty($fullrecord)){

            foreach($fullrecord as $record) {
                if($record->groceryTaskMerchants) {
                    if ($record->groceryTaskMerchants->end_time != NULL) {
                        $endTime = (new \DateTime($record->groceryTaskMerchants->end_time))->setTimezone(new \DateTimeZone('America/Toronto'))->format('H:i:s');
                        if ($record->grocery_delivery_time) {
                            if(date('Y-m-d',strtotime($record->dropoff_eta)).' '.date('H:i:s',strtotime($record->groceryTaskMerchants->end_time)+300) > date('Y-m-d H:i:s',strtotime($record->grocery_delivery_time->delivery_time))){
                                $otd++;
                            }
                        }else {
                            $otd++;
                        }
                    }
                    else {
                        $otd++;
                    }
                }

                $totalorder++;

            }
           // dd($otd);
            /*if($totalorder==0){
                $totalorder=1;
            }*/
        }

        $total_page=1;

        $html =  view('backend.groceryDashboard.sub-views.ajax-render-view-grocery-orders',compact('fullrecord','page','total_page','totalorder','otd'))->render();
        return response()->json(array('status' => true,'for'=>'grocery-orders','html'=>$html));
    }


    /**
     * Get Grocery new orders count
     */
        public function groceryNewCount()
    {
        $current_date = date("Y-m-d H:i:s");
        $pervious_date = date('Y-m-d H:i:s',strtotime('-20 seconds',strtotime(date("Y-m-d H:i:s"))));
        $pervious_count = \Illuminate\Support\Facades\DB::table('sprint__sprints')->whereIn('creator_id', [477164,477484])->where('created_at', '<=', $pervious_date)->count();
        $new_count = \Illuminate\Support\Facades\DB::table('sprint__sprints')->whereIn('creator_id', [477164,477484])->where('created_at', '<=', $current_date)->count();
        return $new_count - $pervious_count;

    }
   

   


}
