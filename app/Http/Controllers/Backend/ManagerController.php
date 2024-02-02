<?php

namespace App\Http\Controllers\Backend;


use App\Manager;
use Carbon\Carbon;
use App\FinanceVendorCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManagerController extends BackendController
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        
        $hub_id=$request->get('hub_id');
        $managers = Manager::where('deleted_at', null)->get();
        if(!empty($hub_id) && $hub_id!='' && $hub_id!=null){
            $hub_id=base64_decode($hub_id);
            $managers = Manager::where('deleted_at', null)->where('hub_id', $hub_id)->get();
        }
        $hubs = FinanceVendorCity::where('deleted_at', null)->get()->toArray();
        
        
        foreach ($managers as $key => $manager) {
            $managers[$key]['hub_name']=($manager->financeVendorCity!=null)?$manager->financeVendorCity->city_name:'';
        }
        // dd(count($managers));
        return backend_view('manager.index',compact('hubs','managers'));
    }
    public function store(Request $request)
    {   
        $this->validate($request,[
            'name' => 'required|max:255',
            'hub' => 'required',
        ]);
        $data=$request->all();
        $data['hub_id'] = $data['hub'];
        unset($data['hub']);
        unset($data['_token']);
        Manager::create($data);
        session()->flash('alert-success', 'Manager has been created successfully!');
        return redirect()->route('manager.index');
    }
    public function update(Request $request,$id)
    {   
        $this->validate($request,[
            'name' => 'required|max:255',
            'hub' => 'required',
        ]);
        $data=$request->all();
        $data['hub_id'] = $data['hub'];
        unset($data['hub']);
        unset($data['_token']);
        unset($data['_method']);
        Manager::where("id",$id)->update($data);
        session()->flash('alert-success', 'Manager has been updated successfully!');
        return redirect()->route('manager.index');
    }
}
