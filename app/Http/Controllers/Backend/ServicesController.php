<?php

namespace App\Http\Controllers\Backend;

use Config;

use Illuminate\Http\Request;

// use App\Http\Requests;
use App\Http\Requests\Backend\CategoryRequest;
use Illuminate\Support\Facades\Request as FacadeRequest;
use App\Http\Controllers\Backend\BackendController;
use Illuminate\Support\Facades\Auth;
//use Validator;

use App\Service;
use App\ProductPrice;
use App\UserEntities;

class ServicesController extends BackendController
{
    public function getIndex()
    {
        $services = Service::get();

        return backend_view( 'services.index', compact('services') );
    }

    public function edit(Service $service)
    {
        return backend_view('services.edit', compact('service'));
    }

    public function add()
    {

        return backend_view('services.add');
    }

    public function create(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('service_image')) {
            
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('service_image')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.servicePicPath'));
            $request->file('service_image')->move($path, $imageName);

            $data['service_image'] = $imageName;
        }
        else{
          
            $imageName="default.png";
            $data['service_image'] = $imageName;
        }
        $dataPrice['mandi_price'] = $data['mandi_price'];
        $dataPrice['buying_price'] = $data['buying_price'];
        $dataPrice['selling_price'] = $data['selling_price'];
        unset($data['mandi_price']);
        unset($data['buying_price']);
        unset($data['selling_price']);
        $id = Service::create( $data );
        $dataPrice['product_id'] = $id;
        ProductPrice::create($dataPrice);

        session()->flash('alert-success', 'Service has been created successfully!');
            return redirect( 'backend/service/add/');
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->all();
        $service->update( $data );

        session()->flash('alert-success', 'Service has been updated successfully!');
        return redirect( 'backend/service/edit/' . $category->id );
    }

    public function destroy(Service $service)
    {   
        $id = $service->id	;	
        $service->delete();

        session()->flash('alert-success', 'Service has been deleted successfully!');
        return redirect( 'backend/services' );
    }

}
