<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class BackOfficeUserRequest extends FormRequest
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
        $route = Route::current();

        if ($route->getName() == 'auth.back-office-user.login') {
            return [
                'mobile' => 'required|digits:11|regex:/^[0-9]*$/'
            ];
        } elseif ($route->getName() == 'auth.back-office-user.login-confirm') {
            return [
                'otp' => 'required|min:6|max:6',
                'mobile' => 'required|digits:11|regex:/^[0-9]*$/'
            ];
        }
    }
}
