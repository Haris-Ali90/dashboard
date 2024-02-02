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

use App\Zone;
use App\UserEntities;

class ZoneController extends BackendController
{
    public function getIndex()
    {
        $zones = Zone::all();

        return backend_view( 'zone.index', compact('zones') );
    }

    public function edit(Zone $zone)
    {
        return backend_view('zone.edit', compact('zone'));
    }

    public function add()
    {
        return backend_view('zone.add');
    }

    public function create(Request $request)
    {
        $data = $request->all();
      /* if ($request->hasFile('product_image')) {
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('product_image')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.zonePicPath'));
            $request->file('product_image')->move($path, $imageName);

            $data['product_image'] = $imageName;
        }
        else
        {
            $imageName="default.png";
            $data['product_image'] = $imageName;
            
        }*/
        Zone::create( $data );

        session()->flash('alert-success', 'Zones has been created successfully!');
            return redirect( 'backend/zone/add/');
    }

    public function update(Request $request, Zone $zone)
    {
        $data = $request->all();
        if ($request->hasFile('product_image')) {
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('product_image')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.zonePicPath'));
            $request->file('product_image')->move($path, $imageName);

            $data['product_image'] = $imageName;
        }
        $zone->update( $data );

        session()->flash('alert-success', 'Zones has been updated successfully!');
        return redirect( 'backend/zone/edit/' . $zone->id );
    }

    public function destroy(Zone $zone)
    {   
        $id = $zone->id ;   
        $zone->delete();
        Zone::where('id',$id)->delete();

        session()->flash('alert-success', 'Zones has been deleted successfully!');
        return redirect( 'backend/zones' );
    }

}
