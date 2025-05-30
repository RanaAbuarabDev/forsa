<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Enums\PostType;
use Illuminate\Validation\Rules\Enum;

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
            'type' => ['required', new Enum(PostType::class)],
            'description' => 'required|string',
            'governorate_id' => 'required|exists:governorates,id',
            'experience' => 'required|string|max:100',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string',
            'job_type' => 'nullable|in:training,volunteer,temporary,full-time,part-time,contracts,free-work',
        ];

        if ($this->input('type') === 'job_creation') {
            $rules['online'] = 'nullable|boolean';
            $rules['salary'] = 'nullable|string|max:50';
        }
    
        return $rules;
    }
}
