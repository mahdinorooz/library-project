<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditProfileRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id=auth()->user()->id;
        return
        [
            'first_name' => 'required',
            'last_name' => 'required',
            'national_code' => 'required|unique:users',
            'mobile' => "required|unique:users,mobile,{$id}",
            'address' => 'required',
            'degree' => 'required',
            'email' => 'required|email|unique:users',
        ];
    }
}
