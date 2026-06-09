<?php

namespace Modules\Exam\App\Http\Controllers\Api;

use App\Imports\ExamsImport;
use Illuminate\Http\Request;
use Modules\Exam\DTO\ExamDto;
use Illuminate\Http\JsonResponse;
use Modules\Exam\App\Models\Exam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Exam\Service\ExamService;
use Modules\Enrollment\App\Models\Enrollment;
use Modules\Exam\App\Http\Requests\ExamRequest;
use Modules\Course\App\Http\Requests\CourseImportRequest;

class ExamAdminController extends Controller
{
    public function __construct(private ExamService $examService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Trainer');
    }
    public function index(Request $request): JsonResponse
    {
        $exams = $this->examService->findAll($request->all(), ['enrollment']);
        return returnMessage(true, 'Exams Fetched Successfully.', $exams);
    }


    public function store(ExamRequest $request, Enrollment $enrollment): JsonResponse
    {
        try {
            DB::beginTransaction();
            $enrollmentWithExams = $this->examService->save($enrollment, $request->validated());
            DB::commit();
            return returnMessage(true, 'Exams Created Successfully.', $enrollmentWithExams);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(ExamRequest $request, Exam $exam): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = (new ExamDto($request))->dataFromRequest();
            $exam = $this->examService->update($exam, $data);
            DB::commit();
            return returnMessage(true, 'Exams Updated Successfully.', $exam);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(ExamRequest $request, Exam $exam): JsonResponse
    {
        try {
            DB::beginTransaction();
            $isDeleted = $this->examService->delete($exam);
            DB::commit();
            if ($isDeleted)
                return returnMessage(true, 'Exams Deleted Successfully.');
            else
                return returnMessage(true, 'Could not Delete Exam.');
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(Exam $exam): JsonResponse
    {
        try {
            DB::beginTransaction();
            $exam = $this->examService->toggleActivate($exam);
            DB::commit();
            return returnMessage(true, 'Exam Status Toggled Successfully.', $exam);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function import(CourseImportRequest $request)
    {
        try {
            $import = new ExamsImport();
            Excel::import($import, $request->file('file'));
            if (!empty($import->getFailures()))
                return returnValidationMessage(false, 'Validation Errors', $import->getFailures());
            return returnMessage(true, 'Courses Imported Successfully', null);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
