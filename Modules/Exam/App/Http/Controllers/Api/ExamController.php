<?php

namespace Modules\Exam\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Exam\Service\ExamService;

class ExamController extends Controller
{
    public function __construct(private ExamService $examService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Student');
    }
    public function index(Request $request): JsonResponse
    {
        $exams = $this->examService->findAll($request->all(), ['enrollment']);
        return returnMessage(true, 'Exams Fetched Successfully.', $exams);
    }
}
