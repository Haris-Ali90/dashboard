<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\StoreReasonRequest;
use App\Http\Requests\Backend\UpdateReasonRequest;
use App\Reason;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ReasonController extends BackendController
{
    use ResetsPasswords;

    public function getIndex()
    {
        return backend_view('reason.index');
    }

    /**
     * @param Datatables $datatables
     * @param Request $request
     * @return mixed
     */
    public function ReasonList(Datatables $datatables, Request $request)
    {
        $query = Reason::whereNull('deleted_at');
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('action', static function ($record) {
                return backend_view('reason.action', compact('record'));
            })
            ->make(true);
    }


    public function edit($id)
    {
        $reason = Reason::find($id);
        return backend_view( 'reason.edit', compact('reason') );
    }

    public function add(Reason $reason)
    {
        return backend_view( 'reason.add', compact(
            'reason') );
    }

    public function create(StoreReasonRequest $request,Reason $reason)
    {
        $postData = $request->all();
        $createRecord = [
            'title' => $postData['title'],
        ];
        $reason->create($createRecord);
        session()->flash('alert-success', 'Reason has been created successfully!');
        return redirect( 'reason' . $reason->id );

    }

    public function update(UpdateReasonRequest $request, Reason $reason)
    {
        $postData = $request->all();
        $updateRecord = [
            'title' => $postData['title'],
        ];
        $reason->update($updateRecord);
        session()->flash('alert-success', 'Reason has been updated successfully!');
        return redirect( 'reason');
    }

    public function destroy(Reason $reason)
    {
        $reason->delete();
        session()->flash('alert-success', 'Reason has been deleted successfully!');

        return redirect( 'reason');
    }

}
