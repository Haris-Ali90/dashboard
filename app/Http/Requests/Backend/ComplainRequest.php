<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class ComplainRequest extends Request {

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
            'reason.required' => 'Please select your complain reason'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            'reason' => 'required',
            'complain_data'=>'required'

        ];
        return $rules;
    }
}