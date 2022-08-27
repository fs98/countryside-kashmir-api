<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
                'unique:packages',
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
            'days' => [
                'required',
                'numeric',
                'min:1'
            ],
            'nights' => [
                'required',
                'numeric',
                'min:1'
            ],
            'price' => [
                'nullable',
                'numeric'
            ],
            'category_id' => [
                'required',
                'exists:categories,id'
            ],
            'persons' => [
                'required',
                'numeric',
                'min:1'
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
