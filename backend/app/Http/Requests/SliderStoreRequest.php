<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SliderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'min:3',
            'slider_name' =>'required|mimes:jpg,png,jpeg,JPG,PNG,JPEG|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'slider_name.max' => 'Slider image should be maximum 2MB.'
        ];
    }
}
