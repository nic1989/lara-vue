<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => [
                'required', 'min:3'
            ],
            'email' => 'required|email|unique:users,email,'.$this->route('user')->id,
            'password' => [
                'sometimes',
                'nullable',
                'min:6',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'parent_id' => ['required']
        ];
    }
    
    public function messages()
    {
        return [
            'password.regex' => 'Password must be One Uppercase, One Lowercase, One Number and One Special Character!',
            'parent_id.required' => 'The client field is required'
        ];
    }
}
