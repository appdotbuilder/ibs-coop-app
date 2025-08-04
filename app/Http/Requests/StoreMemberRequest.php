<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'id_card_number' => 'nullable|string|max:20|unique:members,id_card_number',
            'join_date' => 'required|date',
            'share_capital' => 'numeric|min:0',
            'mandatory_savings' => 'numeric|min:0',
            'voluntary_savings' => 'numeric|min:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Member name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'birth_date.before' => 'Birth date must be before today.',
            'id_card_number.unique' => 'This ID card number is already registered.',
            'join_date.required' => 'Join date is required.',
            'share_capital.min' => 'Share capital must be at least 0.',
            'mandatory_savings.min' => 'Mandatory savings must be at least 0.',
            'voluntary_savings.min' => 'Voluntary savings must be at least 0.',
        ];
    }
}