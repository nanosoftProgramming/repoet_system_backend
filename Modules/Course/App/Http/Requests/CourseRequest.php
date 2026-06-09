<?php

namespace Modules\Course\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class CourseRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $admin = auth('user')->user();
        if ($admin->hasRole('Trainer'))
            $this->merge(['trainer_id' => $admin->id]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'trainer_id' => ['required', 'exists:users,id'],
            'sessions_no' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'date' => ['nullable', 'date'],
            'survey_file' => ['nullable', 'file', 'max:1024'],
            'details_file' => ['nullable', 'file', 'max:1024'],
            'student' => ['nullable', 'string'],
            'instructor' => ['nullable', 'string'],
            'trainer' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'sessions_no' => 'Sessions No',
            'price' => 'Price',
            'image' => 'Image',
            'trainer_id' => 'Trainer',
            'date' => 'Date',
            'survey_file' => 'Survey File',
            'details_file' => 'Details File',
            'student' => 'Student',
            'instructor' => 'Instructor',
            'trainer' => 'Trainer',
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
