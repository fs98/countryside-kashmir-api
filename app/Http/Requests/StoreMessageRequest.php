<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:32',
            'last_name' => 'nullable|string|max:32',
            'phone_number' => 'required|string|max:64|',
            'email' => 'nullable|email|max:128',
            'content' => 'nullable|string|max:255',
        ];
    }
}
