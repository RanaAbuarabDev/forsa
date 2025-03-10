<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'title'=>'string|min:3|max:100|nullable|sometimes',
            'description'=>'string|min:3|max:2000|nullable|sometimes',
            'place'=>'string|min:2|max:100|sometimes',
            'salary'=>'integer|nullable|sometimes',
            'Number_of_hours'=>'integer|nullable|sometimes',
            'ApplyStatus'=>'boolian|nullable'
        ];
    }
}
