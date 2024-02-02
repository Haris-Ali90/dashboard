<?php

namespace App\Http\Controllers\Backend;

use App\Walmart;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class LoblawsHomeDeliveryController extends BackendController
{

    /**
     * Get Loblaws Home Delivery
     */
    public function statistics_loblaws_index(Request $request)
    {
        $date=$request->get('date');
       if(empty($date))
       {
           $date=date("Y-m-d");
       }
        return backend_view('loblawshomedelivery.statistics_loblawshomedelivery_dashboard',compact('date'));
    }

    /**
     * Get Loblaws Home Delivery OTD Graph
     */
    public function ajax_render_otd_charts(Request $request)
    {
        $date=$request->get('date');
        $query="SELECT CONCAT(a.sprint_id,'-',a.ordinal-1) AS order_id,end_time,
        (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=17 AND sprint__tasks_id=a.task_id LIMIT 1) AS delivery_time
        FROM
        (SELECT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.contact_id,sprint__tasks.status_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,location_id,ordinal FROM sprint__tasks WHERE TYPE='dropoff' AND sprint__tasks.deleted_at IS NULL) AS a 
        JOIN sprint__sprints ON(a.sprint_id=sprint__sprints.id)
        LEFT JOIN merchantids ON(merchantids.task_id=a.task_id)
        WHERE creator_id IN (477205,477464,477465,477470,477471,477472,477473,477474,477475) AND a.dropoff_eta LIKE '".$date."%' ORDER BY order_id";
        $fullrecord=DB::select($query);
         $total=0;
         $otd=0;
         if(!empty($fullrecord)){
           foreach($fullrecord as $record) { 
               if($record->end_time!=NULL){
                  
                   if(date('H:i:s',strtotime($record->end_time)+900) > date('H:i:s',strtotime($record->delivery_time))){
                       $otd++;
                      }
                      $total++;
               }
               
           }
           if($total==0){
               $total=1;
           }
         }

         $data_set_one = ['y1'=>100,'y2'=>0,"tag1"=>"No Deliveries","tag2"=>"No Deliveries"];
         if($otd==0)
         {
             return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$data_set_one]));
         }
         $data_set_one =  ['y1'=>round((($otd)/$total)*100,0),'y2'=>100-round((($otd)/$total)*100,0),"tag1"=>"On Time Deliveries","tag2"=>"Off Time Deliveries"]; 
    
        return response()->json(array('status' => true,'for'=>'pie_chart', 'data'=>[$data_set_one]));
    }

    /**
     * Get Loblaws Home Delivery Orders
     */
    public function ajax_render_loblaws_orders(Request $request)
    {
        $fullrecord=[];
        $date= ($request->date != null) ? $request->date : date("Y-m-d");
        $page= (empty($request->page)) ? 1 : $request->page;
        // $Records_Per_page= 10;
        // $offset_value = ($page-1) * $Records_Per_page;
        $query="SELECT CONCAT(a.sprint_id,'-',a.ordinal-1) AS order_id,end_time,
        (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=17 AND sprint__tasks_id=a.task_id LIMIT 1) AS delivery_time
        FROM
        (SELECT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.contact_id,sprint__tasks.status_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,location_id,ordinal FROM sprint__tasks WHERE TYPE='dropoff' AND sprint__tasks.deleted_at IS NULL) AS a 
        JOIN sprint__sprints ON(a.sprint_id=sprint__sprints.id)
        LEFT JOIN merchantids ON(merchantids.task_id=a.task_id)
        WHERE creator_id IN (477205,477464,477465,477470,477471,477472,477473,477474,477475) AND a.dropoff_eta LIKE '".$date."%' ORDER BY order_id";
        $fullrecord=DB::select($query);
         $totalorder=0;
         $otd=0;
        //  if($page==1){

            if(!empty($fullrecord)){
                foreach($fullrecord as $record) { 
                    if($record->end_time!=NULL){
                       
                        if(date('H:i:s',strtotime($record->end_time)+900) > date('H:i:s',strtotime($record->delivery_time))){
                            $otd++;
                           }
                           $totalorder++;
                    }
                    
                }
                if($totalorder==0){
                    $totalorder=1;
                }
              }
        //  }
        //  else
        //  {
        //     $totalorder=(int)$request->total_order_value;
        //     $otd=$totalorder-(int)$request->total_late_value;
        //  }
       
            // $query="SELECT a.sprint_id,CONCAT(a.sprint_id,'-',a.ordinal-1) AS order_id,a.status_id AS task_status,
            // (SELECT attachment_path FROM sprint__confirmations WHERE task_id=a.task_id AND title='Upload Image' AND attachment_path IS NOT NULL LIMIT 1) AS image,
            // (SELECT attachment_path FROM sprint__confirmations WHERE task_id=a.task_id AND title='Signature' AND attachment_path IS NOT NULL LIMIT 1) AS signature,
            // CONCAT(joeys.first_name,' ',joeys.last_name) AS joey_name,dropoff_eta,locations.address,FROM_UNIXTIME(merchantids.scheduled_duetime-18000) AS scheduled_duetime,merchantids.merchant_order_num,merchantids.start_time,end_time,sprint__sprints.status_id AS sprint_status,
            // (SELECT DATE_SUB(sprint__tasks_history.created_at,INTERVAL 5 HOUR)  AS arrival_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=67 AND sprint__tasks_history.sprint_id=a.sprint_id LIMIT 1) AS arrival_time,
            // (SELECT DATE_SUB(sprint__tasks_history.created_at,INTERVAL 5 HOUR)  AS departure_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=15 AND sprint__tasks_history.sprint_id=a.sprint_id LIMIT 1) AS departure_time,
            // (SELECT DATE_SUB(sprint__tasks_history.created_at,INTERVAL 5 HOUR)  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=17 AND sprint__tasks_id=a.task_id LIMIT 1) AS delivery_time,
            // (SELECT FROM_UNIXTIME(due_time-18000) FROM sprint__tasks  WHERE TYPE='pickup' AND sprint_id=a.sprint_id) AS arrival_eta,
            // (SELECT sprint__contacts.name FROM  sprint__tasks JOIN sprint__contacts ON (sprint__contacts.id=sprint__tasks.contact_id) WHERE TYPE='pickup' AND sprint__tasks.sprint_id=a.sprint_id LIMIT 1 ) AS name
            // FROM
            // (SELECT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.contact_id,sprint__tasks.status_id,FROM_UNIXTIME(eta_time-18000) AS dropoff_eta,location_id,ordinal FROM sprint__tasks WHERE TYPE='dropoff' AND sprint__tasks.deleted_at IS NULL) AS a 
            // JOIN sprint__sprints ON(a.sprint_id=sprint__sprints.id)
            // LEFT JOIN joeys ON(joeys.id=joey_id)
            // JOIN locations ON (a.location_id=locations.id) 
            // LEFT JOIN merchantids ON(merchantids.task_id=a.task_id)
            // WHERE creator_id IN (477205) AND CONVERT_TZ(a.dropoff_eta,'UTC','America/Toronto') LIKE '".$date."%' ORDER BY order_id";
            // LIMIT ". $Records_Per_page. " OFFSET ".$offset_value."";
            $query= "SELECT a.sprint_id,a.ordinal,
            (SELECT attachment_path FROM sprint__confirmations WHERE task_id=a.task_id AND title='Upload Image' AND attachment_path IS NOT NULL LIMIT 1) AS image,
            (SELECT attachment_path FROM sprint__confirmations WHERE task_id=a.task_id AND title='Signature' AND attachment_path IS NOT NULL LIMIT 1) AS signature,
            CONCAT(joeys.first_name,' ',joeys.last_name) AS joey_name,dropoff_eta,locations.address,merchantids.merchant_order_num,merchantids.start_time,end_time,sprint__sprints.status_id AS sprint_status,
            (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS arrival_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=67 AND sprint__tasks_history.sprint_id=a.sprint_id LIMIT 1) AS arrival_time,
            (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS departure_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=15 AND sprint__tasks_history.sprint_id=a.sprint_id LIMIT 1) AS departure_time,
            (SELECT CONVERT_TZ(sprint__tasks_history.created_at,'UTC','America/Toronto')  AS delivery_time FROM sprint__tasks_history WHERE sprint__tasks_history.status_id=17 AND sprint__tasks_id=a.task_id LIMIT 1) AS delivery_time,
            (SELECT CONVERT_TZ(FROM_UNIXTIME(due_time),'UTC','America/Toronto') 
            FROM sprint__tasks  WHERE TYPE='pickup' 
            AND sprint_id=a.sprint_id
            AND sprint__tasks.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
            LIMIT 1) AS arrival_eta,
            (SELECT sprint__contacts.name FROM  sprint__tasks JOIN sprint__contacts ON (sprint__contacts.id=sprint__tasks.contact_id) WHERE TYPE='pickup' AND sprint__tasks.sprint_id=a.sprint_id LIMIT 1 ) AS store_name
            FROM
            (SELECT sprint__tasks.id AS task_id,sprint__tasks.sprint_id,sprint__tasks.contact_id,CONVERT_TZ(FROM_UNIXTIME(eta_time),'UTC','America/Toronto') AS dropoff_eta,location_id,ordinal 
            FROM sprint__tasks 
            WHERE TYPE='dropoff' 
            AND sprint__tasks.deleted_at IS NULL
            AND sprint__tasks.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
            ) AS a 
            JOIN sprint__sprints ON(a.sprint_id=sprint__sprints.id)
            LEFT JOIN joeys ON(joeys.id=joey_id)
            JOIN locations ON (a.location_id=locations.id) 
            JOIN merchantids ON(merchantids.task_id=a.task_id)
            WHERE creator_id IN (477205,477464,477465,477471,477472,477473,477474,477475) 
            AND a.dropoff_eta LIKE '".$date."%'
            AND sprint__sprints.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
            AND merchantids.created_at > DATE_SUB('".$date."', INTERVAL 2 DAY)
            ORDER BY a.sprint_id";

             $fullrecord = DB::select($query); 
      
        

        
              $total_page=1;
              //ceil($totalorder/$Records_Per_page);    
       
        
       
        $html =  view('backend.loblawshomedelivery.sub-views.ajax-render-view-loblaws-orders',compact('fullrecord','page','total_page','totalorder','otd'))->render();
        return response()->json(array('status' => true,'for'=>'loblawshomedelivery-orders','html'=>$html));
    }

    /**
     * Get Loblaws Home Delivery OTA Graph
     */
    public function ajax_render_ota_charts(Request $request)
    {
        $date=$request->get('date');
        $query5 = "select
        count(case when sprint__tasks_history.status_id=67  then 1 else null end) as arr,
        count(case when sprint__tasks_history.status_id=68  then 1 else null end) as del,
        count(case when (sprint__tasks_history.status_id=67) and (from_unixtime(due_time)>sprint__tasks_history.created_at)  then 1 else null end) as ota,
        count(case when (sprint__tasks_history.status_id=68) and (from_unixtime(due_time)>sprint__tasks_history.created_at)  then 1 else null end) as otd
        from sprint__sprints 
        join sprint__tasks on(sprint__tasks.sprint_id=sprint__sprints.id )
        join sprint__tasks_history on(sprint__tasks_history.sprint_id=sprint__tasks.sprint_id) 
        where CONVERT_TZ(sprint__sprints.created_at,'UTC','America/Toronto') like '".$date."%' and 
        sprint__sprints.deleted_at IS NULL 
        and sprint__sprints.active=0
        and creator_id in (477205,477464,477465,477470,477471,477472,477473,477474,477475) 
        and type='pickup' ";
  
        $otadata = DB::select($query5);
 
        $otas = [];
        $otds = [];
        if(!empty($otadata)) {
            foreach($otadata as $data){
            
            if($data->arr!=0){
                $otas[] = round(($data->ota/$data->arr)*100,0);
            }

            if($data->del!=0) 
            {
                $otds[] = round(($data->otd/$data->del)*100,0);
            }
            }
        }
       
        return response()->json(array('status' => true,'for'=>'loblawshomedelivery-ota','data_set_one' => 
       // [60],
         $otas,
        //'data_set_two' => [40]
            $otds
        ));

    }

}
