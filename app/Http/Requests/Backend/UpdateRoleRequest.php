<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;
use App\Roles;
use Illuminate\Validation\Rule;


class UpdateRoleRequest extends Request
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

        /*return [
            'name' => 'required|unique:roles,display_name,'.$request['id'].'',
        ];*/

        return [
            'display_name'=> 'required|unique:roles,display_name,'.$request['id'].',id,type,'.Roles::ROLE_TYPE_NAME.'',
            /*'display_name' => 'required|unique:roles,display_name',*/
//            'name' =>[
//                'required',
//                Rule::unique('roles','display_name')->where(function ($query) {
//                    return $query->where('type', Roles::ROLE_TYPE_NAME);
//                })
//                ->ignore($request['id'])
//            ],

        ];

    }


}
