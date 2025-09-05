<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'message' => 'sometimes|required|string',
            'status' => ['nullable', Rule::in(['new', 'in_progress', 'done', 'rejected'])],
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
            'name.required' => 'Имя обязательно, если указано.',
            'email.email' => 'Поле email должно быть корректным email-адресом.',
            'message.required' => 'Сообщение обязательно, если указано.',
            'status.in' => 'Статус должен быть одним из: new, in_progress, done, rejected.',
            'source_id.exists' => 'Указанный источник не существует.',
        ];
    }

}
