<?php

namespace Modules\Question\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuestionCategoryRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $user = auth('user')->user();
        if ($user && $user->hasRole('Instructor')) {
            $this->merge(['instructor_id' => $user->id]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['nullable', 'string', 'max:255'],
            'questions' => ['nullable', 'array'],
            'questions.*' => ['required_with:questions', 'string'],
            'instructor_id' => ['nullable', 'exists:users,id'],
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'questions' => 'Questions',
            'questions.*' => 'Question',
            'instructor_id' => 'Instructor',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
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
