<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Course\App\Models\Course;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CoursesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    public function collection(Collection $rows): void
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                Course::create([
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'trainer_id' => auth('user')->id(),
                    'sessions_no' => $row['sessions_no'],
                    'price' => $row['price'],
                    'date' => $row['date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
            'title' => ['nullable'],
            'description' => ['nullable'],
            'sessions_no' => ['nullable'],
            'price' => ['nullable'],
            'date' => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'sessions_no' => 'Sessions No',
            'price' => 'Price',
            'date' => 'Date',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'title.string' => 'The Title field must be a string.',
            'username.required' => 'The Username field is required.',
            'sessions_no.integer' => 'The Sessions No field must be an integer.',
            'price.numeric' => 'The Price field must be a numeric value.',
            'date.date' => 'The Date field must be a valid date.',
        ];
    }
}
