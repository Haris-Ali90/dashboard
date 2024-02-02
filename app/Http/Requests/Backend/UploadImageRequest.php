<?php
namespace App\Http\Requests\Backend;

use App\Http\Requests\Request as Request;

class UploadImageRequest extends Request {

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
            'sprint_image.image'  => 'Upload file must be an Image.!',
            'sprint_image.mimes'  => 'The upload file must be a file of type: jpeg, png, jpg, gif.',
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
            'status_id'  => 'required',
            'sprint_image'   => 'required|image|mimes:jpeg,png,jpg,gif',
        ];

    }
}