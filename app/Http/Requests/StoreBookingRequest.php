<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
                'max:64'
            ],
            'email' => [
                'nullable',
                'email',
                'max:128'
            ],
            'phone_number' => [
                'required',
                'string',
                'max:128'
            ],
            'address' => [
                'required',
                'string',
                'max:128'
            ],
            'city' => [
                'required',
                'string',
                'max:128'
            ],
            'country' => [
                'required',
                'string',
                'max:128'
            ],
            'persons' => [
                'required',
                'numeric'
            ],
            'adults' => [
                'required',
                'numeric'
            ],
            'children' => [
                'required',
                'numeric'
            ],
            'arrival_date' => [
                'required',
                'date'
            ],
            'days' => [
                'required',
                'numeric'
            ],
            'nights' => [
                'required',
                'numeric'
            ],
            'package_id' => [
                'nullable',
                'exists:packages,id'
            ]
        ];
    }
}
