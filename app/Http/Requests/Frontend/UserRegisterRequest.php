<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class UserRegisterRequest extends Request {

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
            'gender'  => 'required|string|max:100',
            'phone'  => 'required|string|min:12|max:15',
            'area'  => 'required|string',
            'latitude'  => 'required|string|max:50',
            'longitude'  => 'required|string|max:50',
            'password'   => 'required|string|min:8|confirmed|max:35',
            'prefix'  => 'required|string|max:30',
            'background'  => 'required|string',
            'year_of_experience'=>'required|string|max:50',
            'qualification'  => 'required|string|max:50',
            'hospital'   => 'required|string',
            'category_id'=>'required|integer',
            'profile_picture'=>'required',
        ];
    }
}