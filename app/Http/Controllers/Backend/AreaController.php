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

use App\Area;
use App\Zone;
use App\UserEntities;

class AreaController extends BackendController
{
    /**
     * Get Areas
     */
    public function getIndex()
    {
        $area = Area::with('zone')->get();
        return backend_view( 'area.index')->with('area',$area);
    }

    /**
     * Get Area edit form
     */
    public function edit(Area $area)
    {
        return backend_view('area.edit', compact('area'));
    }

    /**
     * Get Area add form
     */
    public function add()
    {
        return backend_view('area.add');
    }

    /**
     * create Area
     */
    public function create(Request $request)
    {
        $data = $request->all();

        Area::create( $data );

        session()->flash('alert-success', 'Area has been created successfully!');
            return redirect( 'backend/area/add');
    }

    /**
     * Update Area
     */
    public function update(Request $request, Area $area)
    {
        $data = $request->all();
        $area->update( $data );

        session()->flash('alert-success', 'Area has been updated successfully!');
        return redirect( 'backend/area/edit/' . $area->id );
    }

    /**
     * Delete Area
     */
    public function destroy(Area $area)
    {   
        $id = $area->id ;   
        $area->delete();
        Area::where('id',$id)->delete();

        session()->flash('alert-success', 'Area has been deleted successfully!');
        return redirect( 'backend/area' );
    }

}
