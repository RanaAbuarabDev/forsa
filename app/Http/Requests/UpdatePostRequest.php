<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
            'description' => 'string',
            'governorate_id' => 'exists:governorates,id',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            
           
            'salary'=> 'nullable|string|max:50',
            'work_mode' => ['nullable', Rule::in(['full_time', 'part_time','permanent','temporary'])],
            'job_type' => ['nullable', Rule::in(['online', 'on_site','onlin_or_onSite'])],
            'is_bookable' => 'nullable|boolean',
        ];
    }
}
