<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class SubSpeciallityRequest extends Request {

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
            'title.required' => 'Sub Speciality is required.',
            'title_ar.required' => 'Arabic Sub Speciality is required.'
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
            'title' => 'required',
            'title_ar' => 'required',

        ];
        return $rules;
    }
}