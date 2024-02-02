<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class ChangePasswordRequest extends Request {

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
            'old_password.required'  => 'Old password is required',
            //   'password.confirmed'    => 'Incorrect password, please try another one.',
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
            'user_id'       => 'required|min:1',
            'password'      => 'required|string|min:8|max:30|confirmed',
            'old_password'  => 'required|string|min:8|max:30'
        ];
    }
}