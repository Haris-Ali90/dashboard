<?php


namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Sprint;
use App\Task;
use App\MerchantIds;
use App\JobRoutes;
use DB;

class RoutificController extends BackendController {

    public function getstatus(){
        return backend_view('routific.hub-updatestatus');
    }
    
	public function getstatusdesc($id){

        $status = array("136" => "Client requested to cancel the order",
        "137" => "Delay in delivery due to weather or natural disaster",
        "118" => "left at back door",
        "117" => "left with concierge",
        "135" => "Customer refused delivery",
        "108" => "Customer unavailable-Incorrect address",
        "106" => "Customer unavailable - delivery returned",
        "107" => "Customer unavailable - Left voice mail - order returned",
        "109" => "Customer unavailable - Incorrect phone number",
        "142" => "Damaged at hub (before going OFD)",
        "143" => "Damaged on road - undeliverable",
        "144" => "Delivery to mailroom",
        "103" => "Delay at pickup",
        "139" => "Delivery left on front porch",
        "138" => "Delivery left in the garage",
        "114" => "Successful delivery at door",
        "113" => "Successfully hand delivered",
        "120" => "Delivery at Hub",
        "110" => "Delivery to hub for re-delivery",
        "111" => "Delivery to hub for return to merchant",
        "121" => "Out for delivery",
        "102" => "Joey Incident",
        "104" => "Damaged on road - delivery will be attempted",
        "105" => "Item damaged - returned to merchant",
        "129" => "Joey at hub",
        "128" => "Package on the way to hub",
        "140" => "Delivery missorted, may cause delay",
        "116" => "Successful delivery to neighbour",
        "132" => "Office closed - safe dropped",
        "101" => "Joey on the way to pickup",
        "32" => "Order accepted by Joey",
        "14" => "Merchant accepted",
        "36" => "Cancelled by JoeyCo",
        "124" => "At hub - processing",
        "38" => "Draft",
        "18" => "Delivery failed",
        "56" => "Partially delivered",
        "17" => "Delivery success",
        "68" => "Joey is at dropoff location",
        "67" => "Joey is at pickup location",
        "13" => "At hub - processing",
        "16" => "Joey failed to pickup order",
        "57" => "Not all orders were picked up",
        "15" => "Order is with Joey",
        "112" => "To be re-attempted",
        "131" => "Office closed - returned to hub",
        "125" => "Pickup at store - confirmed",
        "61" => "Scheduled order",
        "37" => "Customer cancelled the order",
        "34" => "Customer is editting the order",
        "35" => "Merchant cancelled the order",
        "42" => "Merchant completed the order",
        "54" => "Merchant declined the order",
        "33" => "Merchant is editting the order",
        "29" => "Merchant is unavailable",
        "24" => "Looking for a Joey",
        "23" => "Waiting for merchant(s) to accept",
        "28" => "Order is with Joey",
        "133" => "Packages sorted",
        "55" => "ONLINE PAYMENT EXPIRED",
        "12" => "ONLINE PAYMENT FAILED",
        "53" => "Waiting for customer to pay",
        "141" => "Lost package",
        "60" => "Task failure",
        '153' => 'Miss sorted to be reattempt',
        '154' => 'Joey unable to complete the route','155' => 'To be re-attempted tommorow',
        '155' => 'To be re-attempted tomorrow');
        return $status[$id];
    }
	
    public function poststatusupdate(Request $request){
       
        $data=$request->all();
        
   
          foreach($data['tracking_id'] as $d){                 
             
              $task=MerchantIds::where('id','=',$d)->whereNull('deleted_at')->first(['task_id']);
             
              if(!empty($task->task_id)){
                  $task_id=Task::where('id','=',$task->task_id)->whereNull('deleted_at')->first();
                  $requestData['order_id'] = $task_id->sprint_id;
               }

              $statusDescription= $this->getstatusdesc($data['status_id']);  
              $s=Sprint::where('id','=',$requestData['order_id'])->first();
              
              if(!empty($task->task_id)){
                
                //   DispatchData::where('order_id','=',$requestData['order_id'])->update(['status'=>$data['status_id']
                //   ,'status_copy'=>$statusDescription]);
                  Sprint::where('id','=',$requestData['order_id'])->update(['status_id'=>$data['status_id']]);
                  Task::where('id','=',$task->task_id)->update(['status_id'=>$data['status_id']]);
                  $insert='INSERT INTO sprint__tasks_history (sprint_id, sprint__tasks_id,status_id,date,created_at)
                  VALUES ("'.$requestData['order_id'].'","'.$task->task_id.'","'. $data['status_id'].'","'.date('Y-m-d H:i:s').'","'.date('Y-m-d H:i:s').'")';
                  DB::select($insert);
                 $insert='INSERT INTO sprint__sprints_history (sprint__sprints_id, vehicle_id,status_id,date,created_at)
                  VALUES ("'.$requestData['order_id'].'",3,"'. $data['status_id'].'","'.date('Y-m-d H:i:s').'","'.date('Y-m-d H:i:s').'")';
                  DB::select($insert);
               }
                 
            }
 
        return back()->with('success','Status Updated Successfully!');
    }

    public function getdeleteRouteview()
    {
     return backend_view('routific.route-routific-deleted');
    }
 
     public function deleteRouteId(Request $request)
     {
         $id=$request->input('id');
       
         $route_id=JobRoutes::where('id','=',$id)->first();
         if(empty($route_id)){
             return back()->with('error','Route id not found');
         }
         JobRoutes::where('id','=',$id)->update(['deleted_at'=>date("Y-m-d H:i:s")]);
         return back()->with('success','Route Deleted Successfully!');
         
     }

    public function deleteRoute($routeId){
        JoeyRoute::where('id',$routeId)->update(['deleted_at'=>date('y-m-d H:i:s')]);
        return  "Route R-".$routeId." deleted Successfully";
       
    }
}