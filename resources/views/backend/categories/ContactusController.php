<?php

namespace App\Http\Controllers\Backend;

use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Backend\HelpRequest;
// use App\Http\Requests;
use Illuminate\Support\Facades\Request as FacadeRequest;
use App\Http\Controllers\Backend\BackendController;
//use Validator;

use App\ContactUs;



class ContactusController extends BackendController {

    public function getIndex() {

        $getContactUs = ContactUs::all();
        return backend_view('contactus.index', compact('getContactUs'));
    }

    public function edit(Help $help) {
        /* if ( !$user->isAdmin() )
          abort(404); */
      return backend_view('helps.edit', compact('help'));
    }

    public function add() {

        return backend_view('helps.add');
    }

    public function create(HelpRequest $request) {

        $data = $request->all();

        Help::create($data);

        session()->flash('alert-success', 'Help has been created successfully!');
        return redirect('backend/help/add/');
    }

    public function update(HelpRequest $request,  Help $help) {

        $data = $request->all();
        $help->update($data);

        session()->flash('alert-success', 'Help has been updated successfully!');
        return redirect('backend/help/edit/' . $help->id);
    }

    public function destroy(contactUs $contactus) {

        $contactus->delete();

        session()->flash('alert-success', 'Record deleted successfully!');
        return redirect('backend/contactus');
    }



}
