<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class ModelRequest extends Request {

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
            //'email.unique'    => 'Email already found in our system, please try another one.',
            'name.unique'     => 'Manufacture name already exist.',
            'manufacture_id.required' => 'Please select vehicle manufacture.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'manufacture_id' => 'required',
            //'email' => 'required|email|unique:users,email',
            //'name' => 'required|unique:vehicle_manufactures,name',
            //'password' => 'required|string|min:6',
            //'confirm_password' => 'string|min:6|same:password',
            //'last_name' => 'string',
        ];

        switch ( self::getMethod() ) {
            case 'PUT': // Edit/Update
                //$rules['email'] = 'required|email|unique:users,email,' . collect(self::segments())->last() . ',id';
                //$rules['name'] = 'required|unique:vehicle_manufactures,name,'. collect(self::segments())->last() . ',id';
                // $rules['password'] = 'string|min:6';
                break;
            case 'POST': // New
                //$rules['password'] = 'required|string|min:6';
                break;
        }

        return $rules;
    }
}