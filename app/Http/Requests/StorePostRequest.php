<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
        $rules = [
            'type' => 'required|in:job_creation,announcement',
            'description' => 'required|string',
            'governorate_id' => 'required|exists:governorates,id',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string',
        ];

        if ($this->type === 'job_creation') {
            $rules['work_mode'] = 'nullable|in:temporary,permanent,part_time,full_time';
            $rules['job_type'] = 'nullable|in:onlin_or_onSite,on_site,online';
            $rules['is_bookable'] = 'nullable|boolean';
            $rules['salary'] = 'nullable|string|max:50';
        }
    
        return $rules;
    }
}
