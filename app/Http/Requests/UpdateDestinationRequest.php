<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDestinationRequest extends FormRequest
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
            'name' => 'string|unique:destinations|max:32',
            'description' => 'string',
            'image' => 'image|mimes:jpg,png,jpeg|max:5000',
            'image_alt' => 'string|max:64',
            'keywords' => 'string|max:255',
            'author_id' => 'exists:authors,id'
        ];
    }
}
