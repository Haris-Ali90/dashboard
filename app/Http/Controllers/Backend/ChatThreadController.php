<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;


class ChatThreadController extends BackendController
{
    public function index(Request $request)
    {
        return backend_view('threads');
    }


}
