<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return
                [
                    'name' => 'required|string|max:255',
                    'author' => 'required|string|max:255',
                    'description' => 'required|string|max:1000',
                    'price' => 'required|integer|max:9999999999999999999',
                    'release_date' => 'required',
                    'number_of_pages' => 'required|numeric',
                    'type_id' => 'required|numeric',
                    'is_locked' => 'numeric|in:0,1',
                ];
        } else {
            return
                [
                    'name' => 'required|string|max:255',
                    'author' => 'required|string|max:255',
                    'description' => 'required|string|max:1000',
                    'release_date' => 'required',
                    'number_of_pages' => 'required|numeric',
                    'status' => 'numeric|in:0,1',
                ];
        }
    }
}
