<?php

namespace App\Http\Requests;

class ContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|max:255',
            'email' => 'required|email',
            'phone'       => 'required|numeric',
            'message'       => 'max:2048',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'name.required'   => 'Please enter your full name',
            'name.max'        => 'Please enter your full name maximum of 255 characters',
            'email.required'  => 'Please enter your email',
            'email.email'     => 'Please enter your valid email',
            'phone.required'     => 'Please enter your phone',
            'phone.numeric'       => 'Please enter valid phone number',
        ];
    }
}
