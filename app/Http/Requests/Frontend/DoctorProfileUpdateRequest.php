<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;
use App\Http\Traits\JWTUserTrait;

class DoctorProfileUpdateRequest extends Request
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
//            'email.unique'             => 'Email already found in our system, please try another one.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userInstance = JWTUserTrait::getUserInstance();

        return [
            'full_name'  => 'required|string|max:50',
            'prefix'  => 'required|string|max:30',
            'background'  => 'required|string',
            'area'  => 'required|string',
            'user_id'   => 'required|integer',
            'year_of_experience'=>'required|string|max:50',
            'qualification'  => 'required|string|max:50',
            'hospital'   => 'required|string',
            'category_id'=>'required|integer',
            'latitude'  => 'required|string|max:50',
            'longitude'  => 'required|string|max:50',
//            'profile_picture'=>'required',
        ];
    }
}
