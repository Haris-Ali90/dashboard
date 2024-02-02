<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class ChangepwdRequest extends Request {

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

        $rules = [
            'old_pwd' => 'required|string|min:6',
            'new_pwd' => 'required|string|min:8',
            'confirm_pwd' => 'required|string|min:8|same:new_pwd',
        ];
        return $rules;
    }
}