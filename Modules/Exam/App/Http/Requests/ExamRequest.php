<?php

namespace Modules\Exam\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if ($this->route('exam')) {
            if ($this->method() == 'delete')
                return [];
            return [
                'title' => ['nullable', 'string', 'max:255'],
                'score' => ['nullable', 'numeric', 'min:0', 'max:255'],
                'total' => ['nullable', 'numeric', 'min:0', 'max:255'],
            ];
        }
        return [
            'exams' => ['required', 'array'],
            'exams.*.title' => ['required', 'string', 'max:255'],
            'exams.*.score' => ['required', 'numeric', 'min:0', 'max:255'],
            'exams.*.total' => ['required', 'numeric', 'min:0', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'exams' => 'Exams',
            'exams.*.title' => 'Title',
            'exams.*.score' => 'Score',
            'exams.*.total' => 'Total',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth('user')->user();

        if (!$user->hasRole('trainer')) {
            return true;
        }
        $enrollment = $this->route('enrollment');
        if (!$enrollment || !$enrollment->relationLoaded('course')) {
            $enrollment->load('course');
        }
        return $enrollment && $enrollment->course && $enrollment->course->trainer_id === $user->id;
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            returnUnauthorizeMessage(
                false,
                'You are not authorized to create exams scores for this enrollment.',
                null,
                'forbidden'
            )
        );
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
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
