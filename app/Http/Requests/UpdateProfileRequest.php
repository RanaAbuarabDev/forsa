<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'PhonNum' => ['nullable', 'regex:/^(\+?963|0)?9\d{8}$/'],
            'bio' => 'nullable|string|max:255',
            'BD' => 'nullable|date|before:today',
            'governorate_id' => 'nullable|exists:governorates,id',
            'age'=>'nullable|string',
            'skills' => 'nullable|array|min:1',
            'skills.*' => 'string|max:50',
        ];
    }
}
