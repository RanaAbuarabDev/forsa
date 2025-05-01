<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'img' => 'nullable|image|max:2048',
            'PhonNum' => ['nullable', 'regex:/^(\+?963|0)?9\d{8}$/'],
            'bio' => 'nullable|string|max:255',
            'BD' => 'nullable|date|before:today',
            'governorate_id' => 'required|exists:governorates,id',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:50',
        ];
    }
}
