<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
            'birth_date' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', 'max:1024'],
        ];
        if (auth()->check() && auth()->user()->hasRole('Academy')) {
        $rules['academy_id'] = ['required', 'exists:users,id'];
    }

        if ($this->route('user')) {
            $userId = $this->route('user')?->id;
            $rules['email'] = ['nullable', 'email', 'unique:users,email,' . $userId];
            $rules['phone'] = ['nullable', 'string', 'max:255', 'unique:users,phone,' . $userId];
            $rules['username'] = ['nullable', 'string', 'unique:users,username,' . $userId];
            $rules['identity_number'] = ['nullable', 'string', 'unique:users,identity_number,' . $userId];
            $rules['national_number'] = ['nullable', 'string', 'unique:users,national_number,' . $userId];
            // $rules['password'] = ['nullable', 'string'];
$rules['password'] = ['nullable', 'string', 'min:8'];
        } else {
            $rules['email'] = ['nullable', 'email', 'unique:users,email'];
            $rules['phone'] = ['nullable', 'string', 'max:255', 'unique:users,phone'];
            $rules['username'] = ['required', 'string', 'unique:users,username'];
            $rules['identity_number'] = ['nullable', 'string', 'unique:users,identity_number'];
            $rules['national_number'] = ['nullable', 'string', 'unique:users,national_number'];
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email Address',
            'password' => 'Password',
            'phone' => 'Phone',
            'image' => 'Image',
            'identity_number' => 'Identity Number',
            'username' => 'Username',
            'national_number' => 'National Number',
            'birth_date' => 'Birth Date',
            'file' => 'File',
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
