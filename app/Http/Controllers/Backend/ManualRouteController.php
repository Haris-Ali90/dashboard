<?php

namespace App\Http\Controllers\Backend;


use App\TrackingImageHistory;
use App\Task;
use App\Sprint;
use App\BoradlessDashboard;
use App\JoeyRouteLocations;
use Illuminate\Http\Request;



class ManualRouteController extends BackendController{
    /**
     * Get Route orders
     */
    public function getManualRoute(Request $request){
        return backend_view('manualroute.index');
    }

    public function postUpdateManualRoute(Request $request){
        // dd($request);
        $id = $request['route_id'];
        if (strpos($id,',') !== false) {
            $route_id = explode(',',$id);
            $routes = JoeyRouteLocations::whereIn('route_id',$route_id)->pluck('task_id');
        }
        else{
            $routes = JoeyRouteLocations::where('route_id',$id)->pluck('task_id');
        }
        
        #Defined status of tasks
        $sprint_task = Task::whereIn('id',$routes)->whereIn('status_id',[133,124,121])->pluck('id','sprint_id');
        $check = 0;
        $update_count = array(); 
        foreach($sprint_task as $sprint => $task){
            $check = 1;
            Task::where('id',$task)->update(['status_id'=>112]);
            Sprint::where('id',$sprint)->update(['status_id'=>112, 'in_hub_route'=>0]);
            BoradlessDashboard::where('task_id',$task)->update(['task_status_id'=>112]);
            $update_count [] = $sprint;
        }
        if($check == 1){
            return back()->with('success',count($update_count).' orders Updated Successfully!'); 
        }
        else{
            return back()->with('error','No continue status found in this route!'); 
        }
    }

}
