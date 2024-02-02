<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;
use App\Roles;
use Illuminate\Validation\Rule;

class RoleRequest extends Request
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
            'display_name'=> 'required|unique:roles,display_name,NUll,id,type,'.Roles::ROLE_TYPE_NAME.'',
        ];

    }


}
