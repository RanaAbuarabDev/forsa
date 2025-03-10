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
        return [
            'title'=>'string|min:3|max:100|nullable',
            'description'=>'string|min:3|max:2000|nullable',
            'place'=>'required|string|min:2|max:100',
            'salary'=>'integer|nullable',
            'Number_of_hours'=>'integer|nullable',
            'ApplyStatus'=>'boolian|nullable'
        ];
    }
}
