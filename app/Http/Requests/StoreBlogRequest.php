<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'unique:blogs',
                'max:64'
            ],
            'content' => [
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
            ],
            'published_at' => [
                'date',
                'nullable'
            ]
        ];
    }
}
