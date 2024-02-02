<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class InstituteRegisterRequest extends Request {

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
			'email.required'  => 'Email is required',
            'email.unique'    => 'Email already found in our system, please try another one.',
            'password.confirmed'    => 'Password confirmation does not match with password',
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
            'full_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email|max:50',
            'phone'  => 'required|string|min:12|max:15',
            'password'   => 'required|string|min:8|confirmed|max:35',
            'user_name'  => 'required|string|max:50',
            'city'=>'required',
            'country'=>'required',
        ];
    }
}