<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
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
            'image' => 'image|mimes:jpg,png,jpeg|max:5000',
            'order' => 'numeric|min:1',
            'title' => 'nullable|max:64',
            'subtitle' => 'nullable|max:32',
        ];
    }
}
