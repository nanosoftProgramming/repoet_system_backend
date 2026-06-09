<?php

namespace App\Imports;

use Modules\User\App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => bcrypt($row['password']),
                    'phone' => $row['phone'],
                    'role' => 'Student',
                    'username' => $row['username'],
                    'identity_number' => $row['identity_number'],
                    'national_number' => $row['national_number'],
                    'birth_date' => $row['birth_date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $user->assignRole('Student');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * Get all failures
     */
    public function getFailures(): array
    {
        $failures = [];
        foreach ($this->failures() as $failure) {
            $rowKey = "row_{$failure->row()}";
            if (!isset($failures[$rowKey])) {
                $failures[$rowKey] = [];
            }
            foreach ($failure->errors() as $error) {
                $failures[$rowKey][] = $error;
            }
        }
        return $failures;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'username' => ['required', 'unique:users,username', 'distinct'],
            'email' => ['nullable', 'email', 'unique:users,email', 'distinct'],
            'phone' => ['nullable'],
            'password' => ['required'],
            'identity_number' => ['nullable', 'unique:users,identity_number', 'distinct'],
            'national_number' => ['nullable', 'unique:users,national_number', 'distinct'],
            'birth_date' => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'username' => 'Username',
            'password' => 'Password',
            'identity_number' => 'Identity Number',
            'national_number' => 'National Number',
            'birth_date' => 'Birth Date',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.string' => 'The Name field must be a string.',
            'username.required' => 'The Username field is required.',
            'username.string' => 'The Username field must be a string.',
            'username.unique' => 'The Username has already been taken.',
            'email.email' => 'The Email must be a valid email address.',
            'email.unique' => 'The Email has already been taken.',
            'phone.string' => 'The Phone field must be a string.',
            'password.required' => 'The Password field is required.',
            'password.string' => 'The Password field must be a string.',
            'identity_number.string' => 'The Identity Number must be a string.',
            'identity_number.unique' => 'The Identity Number has already been used.',
            'national_number.string' => 'The National Number must be a string.',
            'national_number.unique' => 'The National Number has already been used.',
            'birth_date.date' => 'The Birth Date must be a valid date.',
        ];
    }
}
