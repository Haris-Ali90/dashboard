<?php

namespace App\Http\Controllers\Backend;


use App\Setting;
use Illuminate\Http\Request;
use Config;
use Yajra\Datatables\Datatables;

use App\Roles;
use Illuminate\Support\Facades\Auth;
use App\WarehouseJoeysCount;
use App\FinanceVendorCity;

class SettingController extends BackendController
{

    public function getIndex()
    {
        return backend_view('setting.index');
    }

    /**
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function getListData(Datatables $datatables, Request $request)
    {
        
        $query = Setting::whereNull('deleted_at');
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
            ->editColumn('sorting_time', static function ($record) {
                if ($record->sorting_time) {
                    return $record->sorting_time.' hour';
                } else {
                    return '';
                }
            })
            ->editColumn('pickup_time', static function ($record) {
                if ($record->pickup_time) {
                    return $record->pickup_time.' hour';
                } else {
                    return '';
                }
            })
            /*->editColumn('delivery_time', static function ($record) {
                if ($record->delivery_time) {
                    return $record->delivery_time.' hour';
                } else {
                    return '';
                }
            })*/
            ->addColumn('action', static function ($record) {
                return backend_view('setting.action', compact('record'));
            })
            ->make(true);
    }

   

    public function edit($id)
    {
        $sub_id = base64_decode($id);
        $set = Setting::find($sub_id);
        $hubs=FinanceVendorCity::whereNull('deleted_at')->get();
        return backend_view( 'setting.edit', compact('set','hubs') );
    }

    public function add(Setting $set)
    {
        $hubs=FinanceVendorCity::whereNull('deleted_at')->get();
        return backend_view( 'setting.add', compact(
            'set','hubs') );
    }

    public function create(Request $request,Setting $set)
    {
        $postData = $request->all();
        $this->validate($request,[
            'hub_id' => 'required',
            'sorting_time' => 'required|min:1|max:4',
            'pickup_time' => 'required|min:1|max:4',
            //'delivery_time' =>  'required|min:1|max:4',
        ],[
            'sorting_time.max' => "The sorting time may not be greater than 4 digits.",
            'sorting_time.min' =>"The sorting time should  be greater than 0.",
            'pickup_time.max' => "The pickup time may not be greater than 4 digits.",
            'pickup_time.min' =>"The pickup time should  be greater than 0.",
           // 'delivery_time.max' => "The delivery time may not be greater than 4 digits.",
           // 'delivery_time.min' =>"The delivery time should  be greater than 0."
        ]);


        $postData = $request->all();
        $dataExist= $set->where('hub_id','=',$postData['hub_id'])->first();
        if($dataExist)
        {

           return redirect()->back()->withErrors(['date'=>$dataExist->HubName->city_name." record already exist."])->withInput($postData);
        }
        $CreateRecord = [

            'hub_id' => $postData['hub_id'],
            'sorting_time' => $postData['sorting_time'],
            'pickup_time' => $postData['pickup_time'],
          //  'delivery_time' => $postData['delivery_time']


        ];
        $set->create($CreateRecord);
        session()->flash('alert-success', 'Setting has been created successfully!');
        return redirect( 'setting' . $set->id );

    }

    public function update($id,Request $request, Setting $set)
    {

        $this->validate($request,[
            'hub_id' => 'required',
            'sorting_time' => 'required|min:1|max:4',
            'pickup_time' => 'required|min:1|max:4',
           // 'delivery_time' =>  'required|min:1|max:4',
        ],[
            'sorting_time.max' => "The sorting time may not be greater than 4 digits.",
            'sorting_time.min' =>"The sorting time should  be greater than 0.",
            'pickup_time.max' => "The pickup time may not be greater than 4 digits.",
            'pickup_time.min' =>"The pickup time should  be greater than 0.",
           // 'delivery_time.max' => "The delivery time may not be greater than 4 digits.",
           // 'delivery_time.min' =>"The delivery time should  be greater than 0."
        ]);
        $postData = $request->all();
        $updateRecord = [

            'hub_id' => $postData['hub_id'],
            'sorting_time' => $postData['sorting_time'],
            'pickup_time' => $postData['pickup_time'],
           // 'delivery_time' => $postData['delivery_time']
        ];
        $dataExist=$set->where('hub_id','=',$postData['hub_id'])->where('id','!=',$id)->first();
        if($dataExist)
        {
           return redirect()->back()->withErrors(['date'=>$dataExist->HubName->city_name." record already exist."])->withInput($postData);
        }
        
        // dd($request);
        $set->where('id','=',$id)->update( $updateRecord );

        session()->flash('alert-success', 'Setting has been updated successfully!');
        return redirect( 'setting');
    }



    

    
  

   

  
}
