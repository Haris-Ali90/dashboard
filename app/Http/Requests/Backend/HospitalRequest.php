<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class HospitalRequest extends Request {

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
            //'email.unique'    => 'Email already found in our system, please try another one.',
            //'image.required' => 'Image is required.'
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
            'hospital_name' => 'required',

        ];
        return $rules;
    }
}