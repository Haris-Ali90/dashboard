<?php

namespace App\Http\Controllers\Backend;


use App\BoradlessDashboard;
use App\Joey;
use App\JoeyRoutes;
use App\MerchantIds;
use App\Task;
use Illuminate\Http\Request;


class UpdateJoeyOrderInstructionController extends BackendController{
    /**
     * Get Route orders
     */
    public function index(Request $request){

        $currentDate = date('Y-m-d H:i:s');
        $previousDate = date('Y-m-d H:i:s', strtotime('-15 days', strtotime($currentDate)));

        $routeData=[];
        if(!empty($request->get('joey_id'))){
            $joeyId = $request->get('joey_id');

            $routeData = BoradlessDashboard::whereNull('deleted_at')
                ->whereNotNull('route_id')
                ->whereNotIn('task_status_id', [36])
                ->whereBetween('created_at', [$previousDate,$currentDate])
                ->where('joey_id', $joeyId)
                ->orderBy('id', 'DESC')
                ->get();

//            $routeData = JoeyRoutes::join('joey_route_locations', 'joey_route_locations.route_id', '=', 'joey_routes.id')
//                ->join('sprint__tasks', 'joey_route_locations.task_id', '=', 'sprint__tasks.id')
//                ->join('sprint__sprints', 'sprint__sprints.id', '=', 'sprint__tasks.sprint_id')
//                ->join('merchantids', 'merchantids.task_id', '=', 'joey_route_locations.task_id')
//                ->whereNull('joey_route_locations.deleted_at')
//                ->whereNull('sprint__tasks.deleted_at')
//                ->whereNull('sprint__sprints.deleted_at')
//                ->whereNull('merchantids.deleted_at')
//                ->whereNotIn('sprint__tasks.status_id', [36])
//                ->where('sprint__tasks.type', 'dropoff')
//                ->where('joey_routes.joey_id', $joeyId)->get(['joey_routes.id as route_id', 'merchantids.tracking_id', 'sprint__sprints.id as order_id', 'sprint__tasks.status_id']);
        }

        $joeys = Joey::whereNull('deleted_at')
            ->where('is_enabled', '=', 1)
            ->whereNull('email_verify_token')
            ->whereNOtNull('plan_id')
            ->orderBy('first_name')
            ->get();

        return backend_view('joey_orders.index', compact('routeData','joeys'));
    }

    public function addJoeyOrderInstruction(Request $request)
    {
        $descriptionLength = strlen($request->get('instruction'));
        if($descriptionLength > 500){
            return json_encode(['status' => 400, 'message' => 'Your Description limit is over 500 character']);
        }
        $task = Task::whereNull('deleted_at')->where('id', $request->get('task_id'))->update(['description' => $request->get('instruction')]);
//        $merchantIds = MerchantIds::whereNull('deleted_at')
//            ->where('tracking_id', $request->get('tracking_id'))
//            ->update(['additional_info' => $request->get('instruction')]);

        return json_encode(['status' => 200, 'message' => 'Instruction has been added successfully']);
    }
}
