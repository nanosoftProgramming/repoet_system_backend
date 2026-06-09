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

class ExamsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $user = User::create([
                    'title' => $row['title'],
                    'score' => $row['score'],
                    'total' => $row['total'],
                    'enrollment_id' => $row['enrollment_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $user->assignRole('Trainer');
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
            'title' => ['required'],
            'score' => ['required'],
            'total' => ['required'],
            'enrollment_id' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'score' => 'Score',
            'total' => 'Total',
            'enrollment_id' => 'Enrollment',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'title.required' => 'The Title field is required.',
            'score.required' => 'The Score field is required.',
            'total.required' => 'The Total field is required.',
            'enrollment_id.required' => 'The Enrollment field is required.',
        ];
    }
}
