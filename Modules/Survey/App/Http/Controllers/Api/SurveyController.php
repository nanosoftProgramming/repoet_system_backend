<?php

namespace Modules\Survey\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Survey\App\Http\Requests\SurveyAnswerRequest;
use Modules\Survey\DTO\SurveyAnswerDto;
use Modules\Survey\Service\SurveyService;
use Modules\Survey\App\Models\Survey; 
use Modules\Survey\App\Models\SurveySubmission;
use Modules\Survey\App\Models\SurveyAnswer;

class SurveyController extends Controller
{
    public function __construct(private SurveyService $surveyService)
    {
    $this->middleware(['auth:user', 'role:Student'])
         ->only(['mySubmissions']);
    }
  public function surveys()
{
    $student = auth('user')->user();

    $surveys = Survey::with('questions')
        ->where('status', true)
        ->where(function ($query) use ($student) {
            $query->where('academy_id', $student->academy_id)
                  ->orWhereNull('academy_id'); // الاستبيانات العامة
        })
        ->get();

    return response()->json([
        'success' => true,
        'surveys' => $surveys
    ]);
}

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $questions = $this->surveyService->getQuestions();

        return returnMessage(true, 'Questions fetched successfully.', $questions);
    }
public function store(SurveyAnswerRequest $request): \Illuminate\Http\JsonResponse
{
  $survey = Survey::find($request->survey_id);

    // التحقق من الحالة: إذا كان status يساوي 0، نمنع الإرسال
    if (!$survey || $survey->status == 0) {
        return response()->json([
            'success' => false,
            'message' => 'عذراً، هذا الاستبيان مغلق حالياً ولا يمكن استقبال إجابات.'
        ], 403);
    }
    // 1. إنشاء سجل الاستلام الرئيسي
    $submission = SurveySubmission::create([
    'student_name'   => $request->student_name,
        'answerable_type' => Survey::class,
        'answerable_id'   => $request->survey_id,
    ]);

    // 2. حلقة التكرار لحفظ الإجابات
    foreach ($request->answers as $item) {
        SurveyAnswer::create([
            'survey_submission_id' => $submission->id,
            'survey_question_id'   => $item['survey_question_id'],
            'answer'               => $item['answer'],
        ]);
    }

    // 3. الإرجاع يتم بعد انتهاء حلقة التكرار بالكامل
    return returnMessage(true, 'Answers submitted successfully.');
}


// try {
        //     DB::beginTransaction();
        //     $data = (new SurveyAnswerDto($request))->dataFromRequest();
        //     $this->surveyService->save($data);
        //     DB::commit();

            // return returnMessage(true, 'Answers submitted successfully.');
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return returnMessage(false, $e->getMessage(), null, 'server_error');
        // }
    

    public function mySubmissions(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $data['student_id'] = auth('user')->id();
        $relations = ['answers.question.category', 'answerable'];

        $submissions = $this->surveyService->findAll($data, $relations);

        return returnMessage(true, 'My submissions fetched successfully.', \Modules\Survey\App\resources\SurveySubmissionResource::collection($submissions));
    }

//     public function mySubmissions()
// {
//     $studentId = auth('user')->id();

//     // جلب الإجابات الخاصة بالطالب الحالي مع العلاقات الضرورية
//     $submissions = \Modules\Survey\App\Models\SurveySubmission::with(['answerable', 'answers.question'])
//         ->where('student_id', $studentId)
//         ->get();

//     return response()->json([
//         'success' => true,
//         'data' => $submissions
//     ]);
// }
}
