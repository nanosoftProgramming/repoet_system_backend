<?php

namespace Modules\Question\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Question\App\Http\Requests\QuestionDeleteRequest;
use Modules\Question\App\Models\Question;
use Modules\Question\Service\QuestionService;

class QuestionAdminController extends Controller
{
    public function __construct(private QuestionService $questionService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Instructor');
    }

    public function destroy(QuestionDeleteRequest $request, Question $question): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->questionService->delete($question);
            DB::commit();

            return returnMessage(true, 'Question deleted successfully', null);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
