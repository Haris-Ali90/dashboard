<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class FacebookRequest extends Request {

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
            'full_name'  => 'required|string|max:50',
            'email'      => 'required|email|max:50',
            'gender'  => 'string|max:50',
            'phone'  => 'string|min:12|max:15',
            'area'  => 'string|max:50',
            'password'   => 'string|min:8|max:40',
            'social_media_id'   => 'required|string',
            'social_media_platform'   => 'required|string|max:40',
        ];
    }
}