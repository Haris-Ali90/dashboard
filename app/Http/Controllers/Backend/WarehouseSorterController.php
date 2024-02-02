<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Config;
use Yajra\Datatables\Datatables;

use App\Roles;
use Illuminate\Support\Facades\Auth;
use App\AlertSystem;
use App\FinanceVendorCity;

class WarehouseSorterController extends BackendController
{

    public function getIndex()
    {
        $hubs=FinanceVendorCity::whereNull('deleted_at')->get();
        return backend_view('warehousesorter.index',compact('hubs'));
    }

    /**
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function warehouseSorterList(Datatables $datatables, Request $request)
    {
        
        $query = AlertSystem::query();
        if($request->get('hub_id'))
        {
           
            $query= $query->where('hub_id','=',$request->get('hub_id'));
        }
        if($request->get('month_id'))
        {
           
            $query= $query->where('date','like',"%-".$request->get('month_id')."-%");
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })->addColumn('hub_name', static function ($record) {
                if ($record->hub_id) {
                    return $record->HubName->city_name;
                } else {
                    return '';
                }
            })
            ->addColumn('delivery_percentage', static function ($record) {
                return $record->delivery_percentage;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('warehousesorter.action', compact('record'));
            })
            ->make(true);
    }

   

    public function edit($id)
    {
        $sub_id = base64_decode($id);
        $user = AlertSystem::find($sub_id);
        $role = Roles::where('id','!=','1')
            ->where('type','=','joeyco_dashboard')->orderBy('display_name','ASC')
            ->get();
            $hubs=FinanceVendorCity::whereNull('deleted_at')->get();

        //        dd($user);
        $userPermissoins = Auth::user()->getPermissions();
        $permissions = explode(',',$user->permissions);
        $rights = explode(',',$user->rights);
       
        return backend_view( 'warehousesorter.edit', compact('role','user','hubs','permissions','userPermissoins','rights') );
    }

    public function add(AlertSystem $role)
    {
        $hubs=FinanceVendorCity::whereNull('deleted_at')->get();
        return backend_view( 'warehousesorter.add', compact(
            'role','hubs') );
    }

    public function create(Request $request,AlertSystem $AlertSystem)
    {
        $postData = $request->all();
        $this->validate($request,[
            'hub_id' => 'required',
            'sorting_time'  => 'required',
            'pickup_time'=>"required",
            'delivery_percentage'=>"required"
        ]);

       
        $postData = $request->all();
        // $dataExist=$AlertSystem->where('hub_id','=',$postData['hub_id'])->where('date','=',$postData['date'])->first();
        // if($dataExist)
        // {

        //    return redirect()->back()->withErrors(['date'=>$dataExist->HubName->city_name." Sorter Count Already Exist on this Date."])->withInput($postData);
        // }
        $CreateRecord = [
            'hub_id' => $postData['hub_id'],
            // 'date' => $postData['date'],
            // 'internal_sorter_count' => $postData['internal_sorter_count'],
            // 'brooker_sorter_count' => $postData['brooker_sorter_count'],
            // 'dispensed_route' => $postData['dispensed_route'],
            // 'manager_on_duty' => $postData['manager_on_duty'],
            'delivery_percentage' => $postData['delivery_percentage'],
            'sorting_time' => $postData['sorting_time'],
            'pickup_time' => $postData['pickup_time'],

        ];
         $AlertSystem->create($CreateRecord);
        session()->flash('alert-success', 'Alert system has been added successfully!');
        return redirect( 'alert-system');

    }

    public function update($id,Request $request, AlertSystem $AlertSystem)
    {   
      
        $this->validate($request,[
            // 'hub_id' => 'required',
            'sorting_time'  => 'required',
            'pickup_time'=>"required",
            'delivery_percentage'=>"required"
        ]);
        $postData = $request->all();
        $updateRecord = [
           
            // 'hub_id' => $postData['hub_id'],
            // 'date' => $postData['date'],
            // 'internal_sorter_count' => $postData['internal_sorter_count'],
            // 'brooker_sorter_count' => $postData['brooker_sorter_count'],
            // 'dispensed_route' => $postData['dispensed_route'],
            // 'manager_on_duty' => $postData['manager_on_duty'],
            'delivery_percentage' => $postData['delivery_percentage'],
            'sorting_time' => $postData['sorting_time'],
            'pickup_time' => $postData['pickup_time'],

        ];
        // $dataExist=$AlertSystem->where('hub_id','=',$postData['hub_id'])->where('id','!=',$id)->where('date','=',$postData['date'])->first();
        // if($dataExist)
        // {
        //    return redirect()->back()->withErrors(['date'=>$dataExist->HubName->city_name." Sorter Count Already Exist on this Date."])->withInput($postData);
        // }
        
        // dd($request);
        $AlertSystem->where('id','=',$id)->update( $updateRecord );

        session()->flash('alert-success', 'Alert system has been updated successfully!');
        return redirect( 'alert-system');
    }

    public function destroy($id,AlertSystem $AlertSystem)
    {
    
      
        $data = $AlertSystem->where('id','=',$id)->delete();
      
        session()->flash('alert-success', 'Alert system has been deleted successfully!');

        return redirect( 'alert-system' );
    }

    public function checkForHub(Request $request)
    {
        $hub_id = $request->input('hub_id');
        // echo $hub_id;
        $checkForHub=AlertSystem::where('hub_id',$hub_id)->first();
        if(empty($checkForHub)) {
            return response()->json(['status'=>404]);
        }else{
            return response()->json(['status'=>200,'id'=>$checkForHub->id]);
        }
    }

    
  

   

  
}
