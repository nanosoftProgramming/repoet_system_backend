<?php

namespace Modules\Survey\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Survey\Service\SurveyService;
use Modules\Survey\App\Models\Survey;
use Modules\Survey\App\Models\SurveyAnswer;
use Modules\Survey\App\Models\SurveyQuestion;
use Modules\Survey\App\Models\SurveySubmission;


class SurveyAdminController extends Controller
{
    public function __construct(private SurveyService $surveyService)
    {
$this->middleware('auth:user')->except(['showSurvey']);
    $this->middleware('role:Super Admin|Academy')->except(['showSurvey']);    }

    public function storeSurvey(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:text,rating,boolean',
        ]);

        $academyId = $request->academy_id;
        
        if (auth()->user()->hasRole('Academy')) {
            $academyId = auth()->id();
        }

        $survey = Survey::create([
            'name' => $request->name,
            'academy_id' => $academyId,
            'created_by' => auth()->id(),
            'status' => true
        ]);

        foreach ($request->questions as $item) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question' => $item['question'],
                'type' => $item['type'],
                'survey_question_category_id' => $item['survey_question_category_id'] ?? null,
                'order' => 1
            ]);
        }

        return response()->json([
            'success' => true,
            'survey' => $survey->load('academy')
        ]);
    }

    public function index() 
    {
        $user = auth()->user();

        if ($user->hasRole('Academy')) {
            $surveys = Survey::where('academy_id', $user->id)
                             ->with('academy')
                             ->get();
        } else {
            $surveys = Survey::with('academy')->get();
        }

        return response()->json($surveys);
    }

    public function showSurvey($id)
    {
        $survey = Survey::with(['academy', 'questions'])->find($id);

        if (!$survey) {
            return response()->json(['success' => false, 'message' => 'Survey not found'], 404);
        }

        return response()->json(['success' => true, 'survey' => $survey]);
    }
public function toggleActive($id)
{
    $survey = Survey::findOrFail($id);
    
    // عكس الحالة: إذا كان 1 يصبح 0، وإذا كان 0 يصبح 1
    $survey->status = !$survey->status; 
    $survey->save();

    return response()->json([
        'success' => true,
        'message' => 'تم تحديث حالة الاستبيان بنجاح.',
        'status' => (bool) $survey->status
    ]);
}
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'category_id' => 'required|exists:survey_question_categories,id',
        ]);

        $data = $request->all();
        
        if (auth()->user()->hasRole('Academy')) {
            $data['academy_id'] = auth()->id();
        }

        $question = $this->surveyService->createQuestion($data);
        return returnMessage(true, 'Question created successfully.', $question);
    }

    public function updateSurvey(Request $request, $id)
    {
      $dd("rrrrrr")
//         $request->validate([
//             'name' => 'required|string',
//             'academy_id' => 'nullable|exists:users,id',
//             'questions' => 'required|array|min:1',
//             'questions.*.question' => 'required|string',
//             'questions.*.type' => 'required|in:text,rating,boolean',
//                 ]);

//         $survey = Survey::find($id);
//         if (!$survey) {
//             return response()->json(['success' => false, 'message' => 'Survey not found'], 404);
//         }

//         $survey->update([
//             'name' => $request->name,
//             'academy_id' => $request->academy_id,
//         ]);

//         $survey->questions()->delete();

//         foreach ($request->questions as $item) {
//             SurveyQuestion::create([
//                 'survey_id' => $survey->id,
//                 'question' => $item['question'],
// 'type' => $item['type'],
//                 'survey_question_category_id' => $item['survey_question_category_id'] ?? null,
//                 'order' => 1
//             ]);
//         }

//         return response()->json([
//             'success' => true,
//             'message' => 'Survey updated successfully',
//             'survey' => $survey->load('questions')
//         ]);
    }

    public function getQuestions(Request $request)
    {
        $questions = $this->surveyService->getQuestions();
        return returnMessage(true, 'Questions fetched successfully.', $questions);
    }

    public function getAnswersByCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $answers = $this->surveyService->getAnswersByCategory($request->all());
        return returnMessage(true, 'Answers fetched successfully.', $answers);
    }

    public function deleteSurvey($id)
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return response()->json(['success' => false, 'message' => 'Survey not found'], 404);
        }

        $survey->questions()->delete();
        $survey->delete();

        return response()->json(['success' => true, 'message' => 'Survey deleted successfully']);
    }



    public function testAnswers()
{
    return response()->json([
        'answers_count' => \Modules\Survey\App\Models\SurveyAnswer::count(),
        'submissions_count' => \Modules\Survey\App\Models\SurveySubmission::count(),
    ]);
}

// public function answers()
// {
//     $user = auth()->user();

//     $query = SurveyAnswer::with([
//         'question',
//         'submission.student.academy',
//         'submission.answerable'
//     ]);

//     // 👇 Student
//     if ($user->hasRole('Student')) {
//         $query->whereHas('submission', function ($q) use ($user) {
//             $q->where('student_id', $user->id);
//         });
//     }

//     // 👇 Academy
//     elseif ($user->hasRole('Academy')) {
//         $query->whereHas('submission.student', function ($q) use ($user) {
//             $q->where('academy_id', $user->id);
//         });
//     }

//     // 👇 Super Admin => لا فلترة

//     return response()->json([
//         'success' => true,
//         'data' => $query->latest()->get()
//     ]);
// }

public function answers()
{
    $user = auth()->user();

    $query = SurveySubmission::with([
        'student.academy',
        'answers.question',
        'answerable' // survey
    ]);

    if ($user->hasRole('Student')) {
        $query->where('student_id', $user->id);
    }

    // if ($user->hasRole('Academy')) {
    //     $query->whereHas('student', function ($q) use ($user) {
    //         $q->where('academy_id', $user->id);
    //     });
    // }

    $submissions = $query->get();

    // استخدم الـ Resource هنا
    return response()->json([
        'success' => true,
        'data' => \Modules\Survey\App\resources\SurveySubmissionResource::collection($submissions)
    ]);
}
}