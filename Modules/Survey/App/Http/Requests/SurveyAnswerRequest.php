<?php

namespace Modules\Survey\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SurveyAnswerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.*.survey_question_id' => ['required', 'exists:survey_questions,id'],
            'answers.*.answer' => ['required'],
            'answerable_type' => ['nullable', 'string', 'in:course,instructor,trainer'],
            'answerable_id' => ['nullable', 'integer', 'required_with:answerable_type'],
                    'student_name' => 'required|string|max:255',

        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'answers' => 'Answers',
            'answers.*.survey_question_id' => 'Survey Question',
            'answers.*.answer' => 'Answer',
            'answerable_type' => 'Answerable Type',
            'answerable_id' => 'Answerable ID',
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
