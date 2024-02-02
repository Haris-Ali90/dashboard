<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class StoreSubadminRequest extends Request {

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
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:dashboard_users,email,NULL,id,deleted_at,NULL,role_id,2',
            'password'   => 'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            /*'profile_picture' => 'required',*/
        ];

    }
}