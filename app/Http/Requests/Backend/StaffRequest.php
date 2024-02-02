<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class StaffRequest extends Request {

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
            'email.unique'    => 'Email already found in our system, please try another one.',
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
            'email'       => 'required|email|unique:users,email,',
            'full_name'  =>   'required|string|max:30',
            'gender'     =>   'required|string|max:10|in:male,female',
            'phone'        =>   'required|string|max:30',
            'profession'        =>   'required|string|max:30',
            'area'        =>   'required|string|max:30',
            'background'        =>   'required|string|max:255',
            'prefix'        =>   'required|string|max:30',
            'year_of_experience'   =>   'required|integer|max:60',
            'password'        => 'required|string|max:50',
            'confirmpwd'        => 'required|string|min:8|same:password',
        ];

        switch ( self::getMethod() ) {
            case 'PUT': // Edit/Update
                $rules['email'] = 'required|email|unique:users,email,' . collect(self::segments())->last() . ',id';
                $rules['password'] = 'string|min:8';
                break;
            case 'POST': // New
                $rules['password'] = 'required|string|min:8';
                break;
        }

        return $rules;
    }
}