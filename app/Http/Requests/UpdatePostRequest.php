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
    public function rules(): array{

        $jobTypes = ['training', 'volunteer', 'temporary', 'full-time', 'part-time', 'contracts', 'free-work'];

        return [
            'description' => 'string',
            'governorate_id' => 'exists:governorates,id',
            'experience' => 'nullable|string|max:100',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            
            'online' => 'nullable|boolean',
            'salary'=> 'nullable|string|max:50',
            'job_type' => ['nullable', Rule::in($jobTypes)],
        ];
    }
}
