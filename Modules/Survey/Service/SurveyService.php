<?php

namespace Modules\Survey\Service;

use Modules\Course\App\Models\Course;
use Modules\Survey\App\Models\SurveyAnswer;
use Modules\Survey\App\Models\SurveyQuestion;
use Modules\Survey\App\Models\SurveyQuestionCategory;
use Modules\User\App\Models\User;

class SurveyService
{
    public function getQuestions(): mixed
    {
        return SurveyQuestionCategory::with([
            'questions' => function ($query) {
                $query->orderBy('order');
            }
        ])->orderBy('order')->get();
    }
    // في SurveyService.php
public function createQuestion(array $data)
{
    return \Modules\Survey\App\Models\SurveyQuestion::create($data);
}

    // public function findAll($data = [], $relations = []): mixed
    // {
    //     // $query = \Modules\Survey\App\Models\SurveySubmission::query()
    //     //     ->with($relations);
    //         $query = \Modules\Survey\App\Models\SurveySubmission::query()
    //         ->with(['student', 'academy', 'answers', 'answerable']);

    //     if (isset($data['answerable_id'])) {
    //         $query->where('answerable_id', $data['answerable_id']);
    //     }

    //     if (isset($data['student_id'])) {
    //         $query->where('student_id', $data['student_id']);
    //     }

    //     if (isset($data['is_general'])) {
    //         $query->whereNull('answerable_type')->whereNull('answerable_id');
    //     }

    //     return getCaseCollection($query->latest(), $data);
    // }

    public function findAll($data = [], $relations = []): mixed
{
    // دمج العلاقات الأساسية مع أي علاقات إضافية
    $defaultRelations = ['student', 'academy', 'answers', 'answerable'];
    $allRelations = array_unique(array_merge($defaultRelations, $relations));

    $query = \Modules\Survey\App\Models\SurveySubmission::query()
            ->with($allRelations);

    if (isset($data['answerable_id'])) {
        $query->where('answerable_id', $data['answerable_id']);
    }

    if (isset($data['student_id'])) {
        $query->where('student_id', $data['student_id']);
    }

    if (isset($data['is_general'])) {
        $query->whereNull('answerable_type')->whereNull('answerable_id');
    }

    return getCaseCollection($query->latest(), $data);
}

    public function findById($id)
    {
        return SurveyAnswer::findOrFail($id);
    }

public function save($data)
{
    // الحصول على بيانات الطالب المسجل حالياً
    $studentId = auth('user')->id();
    $academyId = auth('user')->user()->academy_id;
    $surveyId = $data['survey_id'] ?? null; 

    // الحفظ المباشر في جدول submissions
    // تأكد أن 'answerable_type' يطابق المفتاح المستخدم في MorphMap
    $submission = \Modules\Survey\App\Models\SurveySubmission::create([
        'student_id'      => $studentId,
        'academy_id'      => $academyId,
        'answerable_type' => 'survey', 
        'answerable_id'   => $surveyId,
    ]);

    // التحقق من وجود إجابات قبل البدء في الحفظ لتجنب أي خطأ
    if (!empty($data['answers']) && is_array($data['answers'])) {
        foreach ($data['answers'] as $answerData) {
            \Modules\Survey\App\Models\SurveyAnswer::create([
                'survey_submission_id' => $submission->id,
                'survey_question_id'   => $answerData['survey_question_id'],
                'answer'               => $answerData['answer'] ?? null,
            ]);
        }
    }
    
    return $submission;
}

//     public function save($data)
//     {
//         $data['student_id'] = auth('user')->id();
//         $academyId = auth('user')->user()->academy_id;  
//         $surveyId = $data['survey_id'] ?? null;
//         $answers = $data['answers'] ?? [];
//         $answerableType = \Modules\Survey\App\Models\Survey::class;
// $answerableId = $data['survey_id'] ?? null;

//         // $answerableType = $data['answerable_type'] ?? null;
//         // $answerableId = $data['answerable_id'] ?? null;


//         $mappedAnswerableType = null;
//         $finalAnswerableId = null;

//         if ($answerableType) {

//             $mappedAnswerableType = $this->mapAnswerableType($answerableType);
//             $finalAnswerableId = $answerableId;
//         }

//         $submission = \Modules\Survey\App\Models\SurveySubmission::updateOrCreate(
//             [
//                 'student_id' => $data['student_id'],
//                 'answerable_id' => $answerableId,
//                 'answerable_type' => $mappedAnswerableType,
//                 'answerable_id' => $finalAnswerableId,
//             ],
//             [
//             'academy_id' => $academyId, // إضافة هذا السطر
//         ],
//             []
//         );

//         foreach ($answers as $answerData) {
//             $question = SurveyQuestion::findOrFail($answerData['survey_question_id']);

//             SurveyAnswer::updateOrCreate(
//                 [
//                     'survey_submission_id' => $submission->id,
//                     'survey_question_id' => $question->id,
//                 ],
//                 [
//                     'answer' => $answerData['answer'] ?? null,
//                 ]
//             );
//         }
//     }

    public function getAnswersByCategory($data = []): mixed
    {
        $mappedType = null;
        if (isset($data['answerable_type'])) {
            $mappedType = $this->mapAnswerableType($data['answerable_type']);
        }

        $finalMappedType = $mappedType;

        return SurveyQuestionCategory::with([
            'questions.answers' => function ($query) use ($data, $finalMappedType) {
                $query->join('survey_submissions', 'survey_answers.survey_submission_id', '=', 'survey_submissions.id')
                      ->select('survey_answers.*');

                if ($finalMappedType) {
                    $query->where('survey_submissions.answerable_type', $finalMappedType);
                }
                if (isset($data['answerable_id'])) {
                    $query->where('survey_submissions.answerable_id', $data['answerable_id']);
                }
                if (isset($data['is_general'])) {
                    $query->whereNull('survey_submissions.answerable_type')->whereNull('survey_submissions.answerable_id');
                }
            }
        ])->orderBy('order')->get();
    }

    /**
     * Map simple string to full class name
     */
    private function mapAnswerableType(?string $type): ?string
    {
        if (!$type) {
            return null;
        }

        return match ($type) {
            'course' => Course::class,
            'instructor' => User::class,
            'trainer' => User::class,
            default => $type,
        };
    }
}

