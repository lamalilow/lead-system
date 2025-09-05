<?php

namespace App\Http\Requests\Source;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|unique:sources,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название источника обязательно.',
            'name.unique' => 'Источник с таким именем уже существует.',
        ];
    }
}
