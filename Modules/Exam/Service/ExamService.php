<?php

namespace Modules\Exam\Service;

use Modules\Exam\App\Models\Exam;
use Modules\Enrollment\App\Models\Enrollment;

class ExamService
{
    function findAll($data = [], $relations = []): mixed
    {
        $query = Exam::query()
            ->available()
            ->with($relations)
            ->latest();
        return getCaseCollection($query, $data);
    }

    function save(Enrollment $enrollment, array $data): Enrollment
    {
        $dataToBeInserted = [];
        foreach ($data['exams'] as $exam) {
            $dataToBeInserted[] = [
                'enrollment_id' => $enrollment->id,
                'title' => $exam['title'],
                'score' => $exam['score'],
                'total' => $exam['total'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Exam::insert($dataToBeInserted);
        return $enrollment->fresh('exams');
    }

    public function update(Exam $exam, array $data): Exam
    {
        $exam->update($data);
        return $exam->fresh();
    }

    public function delete(Exam $exam): bool
    {
        return $exam->delete();
    }

    public function toggleActivate(Exam $exam): Exam
    {
        $exam->update(['is_active' => !$exam->is_active]);
        return $exam->fresh();
    }
}
