<?php

namespace Modules\CV\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CVFieldTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'in:Student,Instructor,Trainer'],
            'templates' => ['required', 'array'],
            'templates.*.label' => ['required', 'string', 'max:255'],
            'templates.*.field_key' => ['required', 'string', 'max:255', 'regex:/^[a-z_]+$/'],
            'templates.*.is_required' => ['sometimes', 'boolean'],
            'templates.*.order' => ['sometimes', 'integer', 'min:0'],
            'templates.*.placeholder' => ['nullable', 'string', 'max:255'],
            'templates.*.is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'role' => 'Role',
            'templates' => 'Templates',
            'templates.*.label' => 'Label',
            'templates.*.field_key' => 'Field Key',
            'templates.*.is_required' => 'Is Required',
            'templates.*.order' => 'Order',
            'templates.*.placeholder' => 'Placeholder',
            'templates.*.is_active' => 'Is Active',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            returnValidationMessage(
                false,
                trans('validation.rules_failed'),
                $validator->errors()->messages(),
                'unprocessable_entity'
            )
        );
    }
}
