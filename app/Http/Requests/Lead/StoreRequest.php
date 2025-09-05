<?php

namespace App\Http\Requests\Lead;

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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'message' => 'required|string',
            'source_id' => 'nullable|exists:sources,id',
        ];
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();
            if (empty($data['email']) && empty($data['phone'])) {
                $validator->errors()->add('contact', 'Требуется email или телефон.');
            }
        });
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно.',
            'message.required' => 'Сообщение обязательно.',
            'email.email' => 'Некорректный email.',
            'source_id.exists' => 'Источник не найден.',
        ];
    }
}
