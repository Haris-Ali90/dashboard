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
            'parent_id.required'  => 'Parent Id is required',
//            'email.unique'    => 'Email already found in our system, please try another one.',
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
              'parent_id'  => 'required|integer',
//            'email'      => 'required|email|unique:users,email',
//            'gender'  => 'required|string|max:100',
//            'phone'  => 'required|string',
//            'area'  => 'required|string',
//            'password'   => 'required|string|min:8',
//            'profile_picture'=>'required',
        ];
    }
}