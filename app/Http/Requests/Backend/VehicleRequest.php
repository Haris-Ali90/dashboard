<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class VehicleRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages() {
        return [
            'title.required' => 'Vehicle title is required',
            'car_name.required' => 'Car name is required',
            'year.required' => 'Year is required.',
            'manufacturer.required' => 'Manufacturer price is required.',
            'model.required' => 'Model is required.',
            'color.required' => 'Color is required.',
            'type.required' => 'Type is required.',
            'city.required' => 'City is required.',
            'ad_type.required' => 'Ad type is required.',
            'condition.numeric' => 'Please select condition.',

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        if(! is_null(Request::get('ad_type') ) )
        {
            $field =  $this->checkField(Request::get('ad_type'));
        }



        $rules = [
            'title' => 'required|string',
            'car_name' => 'required|string',
            'year' => 'required|numeric|min:1',
            'manufacturer' => 'required|numeric|min:1',
            'model' => 'required|numeric|min:1',
            'color' => 'required|numeric|min:1',
            'type' => 'required|numeric|min:1',
            'city' => 'required|numeric|min:1',
            'mileage' => 'required|string',
            'ad_type' => 'required',
            $field => 'required|min:1',
            //'sale_price' => 'required|numeric',
            //'image1' => 'required|image| mimes:jpeg,jpg,png ',
        ];

        switch (self::getMethod()) {
//            case 'PUT': // Edit/Update
//                $rules['email'] = 'required|email|unique:users,email,' . collect(self::segments())->last() . ',id';
//                $rules['password'] = 'string|min:6';
//                break;
            case 'POST': // New
                $rules['image1'] = 'required|image| mimes:jpeg,jpg,png ';
                break;
        }

        return $rules;
    }


    protected function checkField($ad_type)
    {
        // this could be a switch or any conditional logic and then based on condition return string
        if ($ad_type == 'swap')  {
            $field = 'condition';
        }else {
            $field = 'price';
        }
        return $field;
    }


}
