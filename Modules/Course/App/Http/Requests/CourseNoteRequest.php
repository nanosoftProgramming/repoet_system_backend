<?php

namespace Modules\Course\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourseNoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if ($this->isMethod('Delete')) {
            return [];
        }
        return [
            'note' => ['required'],
            'file' => ['nullable', 'file', 'max:1024'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'note' => 'Note',
            'file' => 'File',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $note = $this->route('note');
        if ($note && $note->trainer_id) {
            $user = auth('user')->user();
            return $user->id == $note->trainer_id;
        }

        return true;
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            returnUnauthorizeMessage(
                false,
                'You can only update or delete notes that you created.',
                null,
                'forbidden'
            )
        );
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
