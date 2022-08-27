<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
                'string',
                'max:64'
            ],
            'email' => [
                'nullable',
                'email',
                'max:128'
            ],
            'phone_number' => [
                'string',
                'max:128'
            ],
            'address' => [
                'string',
                'max:128'
            ],
            'city' => [
                'string',
                'max:128'
            ],
            'country' => [
                'string',
                'max:128'
            ],
            'persons' => [
                'numeric',
                'min:1'
            ],
            'adults' => [
                'numeric',
                'min:1'
            ],
            'children' => [
                'numeric',
                'min:1'
            ],
            'arrival_date' => [
                'date'
            ],
            'days' => [
                'numeric',
                'min:1'
            ],
            'nights' => [
                'numeric',
                'min:1'
            ],
            'package_id' => [
                'nullable',
                'exists:packages,id'
            ]
        ];
    }
}
