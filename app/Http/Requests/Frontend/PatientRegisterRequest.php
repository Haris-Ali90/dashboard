<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class PatientRegisterRequest extends Request {

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
            // 'email.required'        => 'Email is required',
            // 'email.unique'          => 'Email already found in our system, please try another one.',
//            'password.required'    => 'Password is not provided',
            // 'password.confirmed'    => 'Password confirmation does not match with password'
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
            'user_name'  => 'required|string|max:50',
            'email'      => 'required|max:50|email|unique:users,email',
            'password'   => 'required|string|min:8|max:40',
            /*'full_name'  => 'required|string|max:50',*/
            'phone'  => 'required|string|max:50',
            'address'  => 'required|string|max:50',
            /*'country'  => 'required',
            'city'  => 'required',*/
            'role_id'  => 'required'
            
        ];
    }
}