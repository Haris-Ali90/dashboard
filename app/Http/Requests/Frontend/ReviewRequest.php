<?php
namespace App\Http\Requests\Frontend;

use App\Http\Requests\Jsonify as Request;

class ReviewRequest extends Request {

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
//            'user_id.required'  => 'Parent Id is required',
//           'user_id.exists:users,id,user_id'    => 'User found in database',
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
            'user_id'  => 'required|integer',
            'doctor_id'  => 'required|integer',
            'review' => 'required|string|max:255',
            'rate' => 'required|integer|max:5'
        ];
    }
}