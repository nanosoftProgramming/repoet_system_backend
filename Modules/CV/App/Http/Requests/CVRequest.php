<?php

namespace Modules\CV\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\CV\App\Models\CVFieldTemplate;

class CVRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = auth('user')->user();
            if (!$user) {
        return [];
    }

        $role = $user->role;

        $templates = CVFieldTemplate::forRole($role)->ordered()->get();

        $rules = [];

        foreach ($templates as $template) {
            $fieldRules = [];

            // Use 'sometimes' to only validate if field is present (allows partial updates)
            $fieldRules[] = 'sometimes';

            // If field is present and it's required, it must have a value
            if ($template->is_required) {
                $fieldRules[] = 'required';
            }

            $fieldRules[] = 'string';
            $fieldRules[] = 'max:5000';

            $rules['data.'.$template->field_key] = $fieldRules;
        }

        return $rules;
    }

    public function attributes(): array
    {
        $user = auth('user')->user();
            if (!$user) {
        return [];
    }

        $role = $user->role;

        $templates = CVFieldTemplate::forRole($role)->ordered()->get();

        $attributes = [];

        foreach ($templates as $template) {
            $attributes['data.'.$template->field_key] = $template->label;
        }

        return $attributes;
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
