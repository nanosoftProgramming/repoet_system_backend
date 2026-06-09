<?php

namespace Modules\Question\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Question\App\Models\Question;
use Modules\Question\App\Models\QuestionCategory;

class QuestionCategoryService
{
    public function findAll(array $data = [], array $relations = []): Collection|LengthAwarePaginator
    {
        $query = QuestionCategory::query()
            ->with($relations)
            ->latest();

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): QuestionCategory
    {
        return QuestionCategory::with($relations)->findOrFail($id);
    }

    public function save($data): QuestionCategory
    {
        $questions = $data['questions'] ?? [];
        unset($data['questions']);

        $category = QuestionCategory::create($data);

        if (! empty($questions)) {
            $questionData = [];
            foreach ($questions as $question) {
                $questionData[] = [
                    'question' => $question,
                    'question_category_id' => $category->id,
                    'instructor_id' => $data['instructor_id'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Question::insert($questionData);
        }

        return $category->fresh()->load('questions');
    }

    public function update(QuestionCategory $questionCategory, array $data): QuestionCategory
    {
        $questions = $data['questions'] ?? [];
        $instructorId = $data['instructor_id'] ?? null;
        $shouldUpdateQuestions = array_key_exists('questions', $data);
        unset($data['questions']);

        if (! empty($data)) {
            $questionCategory->update($data);
        }

        if ($shouldUpdateQuestions) {
            $questionCategory->questions()->delete();

            if (! empty($questions)) {
                $questionData = [];
                foreach ($questions as $question) {
                    $questionData[] = [
                        'question' => $question,
                        'question_category_id' => $questionCategory->id,
                        'instructor_id' => $instructorId ?? $questionCategory->instructor_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Question::insert($questionData);
            }
        }

        return $questionCategory->fresh()->load('questions');
    }

    public function delete(QuestionCategory $questionCategory): void
    {
        $questionCategory->delete();
    }
}
