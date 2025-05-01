<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResidenceRequest extends FormRequest
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
            'province' => 'required|in:دمشق,حلب,حمص,حماة,اللاذقية,طرطوس,إدلب,الرقة,دير الزور,الحسكة,درعا,السويداء,القنيطرة'
        ];
    }

    public function messages()
    {
        return [
            'province.in' => 'يجب أن تكون المحافظة إحدى المحافظات السورية المسموح بها.'
        ];
    }
}
