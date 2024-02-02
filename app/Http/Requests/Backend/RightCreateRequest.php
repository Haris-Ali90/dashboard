<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;
use Illuminate\Validation\Rule;

class RightCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->all();

        return [
            'portal_name'=> 'required',
            'right_name'=> 'required|unique:rights,display_name,NUll,id,role_name,'.$request["slug_name"].'',

        ];

    }


}
