<?php

namespace Modules\Question\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Question\App\Http\Requests\QuestionCategoryDeleteRequest;
use Modules\Question\App\Http\Requests\QuestionCategoryRequest;
use Modules\Question\App\Models\QuestionCategory;
use Modules\Question\DTO\QuestionCategoryDto;
use Modules\Question\Service\QuestionCategoryService;

class QuestionCategoryAdminController extends Controller
{
    public function __construct(private QuestionCategoryService $questionCategoryService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Instructor');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $relations = ['instructor', 'questions'];
            $categories = $this->questionCategoryService->findAll($data, $relations);

            return returnMessage(true, 'Question categories fetched successfully', $categories);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function store(QuestionCategoryRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = (new QuestionCategoryDto($request))->dataFromRequest();
            $category = $this->questionCategoryService->save($data);
            DB::commit();

            return returnMessage(true, 'Question category created successfully', $category);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(QuestionCategoryRequest $request, QuestionCategory $questionCategory): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = (new QuestionCategoryDto($request))->dataFromRequest();
            $category = $this->questionCategoryService->update($questionCategory, $data);
            DB::commit();

            return returnMessage(true, 'Question category updated successfully', $category);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(QuestionCategoryDeleteRequest $request, QuestionCategory $questionCategory): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->questionCategoryService->delete($questionCategory);
            DB::commit();

            return returnMessage(true, 'Question category deleted successfully', null);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
