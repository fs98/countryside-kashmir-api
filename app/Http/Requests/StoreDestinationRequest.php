<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDestinationRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'unique:destinations',
                'max:32'
            ],
            'description' => [
                'required',
                'string'
            ],
            'image' => [
                'required',
                'image',
                'mimes:jpg,png,jpeg',
                'max:5000'
            ],
            'image_alt' => [
                'required',
                'string',
                'max:64'
            ],
            'keywords' => [
                'required',
                'string',
                'max:255'
            ],
            'author_id' => [
                'required',
                'exists:authors,id'
            ]
        ];
    }
}
