<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;
use App\Http\Traits\JWTUserTrait;

class PatientEditProfileRequest extends Request
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
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {

        return [
            // 'email.required'        => 'Email is required',
            // 'email.unique'          => 'Email already found in our system, please try another one.'
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
            // 'email'      => 'required|max:50|email|unique:users,email',
            'first_name'  => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'phone'  => 'required|max:50',
            'address'  => 'required|string',
            'city'  => 'required|string|max:50',
            'emergency_contact' => 'required|string|max:50',
            'guardian_name' => 'required|string|max:50',
            'guardian_phone' => 'required|string|max:50',
            'education_type' => 'required|string'
        ];
    }
}
