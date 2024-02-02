<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class PatientFacebookRequest extends Request {

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
            'full_name'  => 'required|string|max:50',
//            'email'      => 'required|email|unique:users,email',
            'gender'      => 'string|max:30',
            'dob'  => 'string|max:100',
            'password'   => 'string|min:8|max:40',
            'social_media_id'   => 'required|string',
            'social_media_platform'   => 'required|string|max:40',
        ];
    }
}