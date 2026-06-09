<?php

namespace Modules\Course\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Course\App\Http\Requests\CourseNoteRequest;
use Modules\Course\App\Models\Course;
use Modules\Course\App\Models\CourseNote;
use Modules\Course\DTO\CourseNoteDto;
use Modules\Course\Service\CourseNoteService;

class CourseNoteController extends Controller
{
    public function __construct(private CourseNoteService $courseNoteService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Trainer|Instructor');
    }

    public function index(Request $request): JsonResponse
    {
        $notes = $this->courseNoteService->findAll($request->all(), ['course']);

        return returnMessage(true, 'Notes Fetched Successfully.', $notes);
    }

    public function store(CourseNoteRequest $request, Course $course): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = (new CourseNoteDto($request))->dataFromRequest();
            $course = $this->courseNoteService->save($course, $data);
            DB::commit();

            return returnMessage(true, 'Course Notes Updated Successfully.', $course);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CourseNoteRequest $request, Course $course, CourseNote $note): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = (new CourseNoteDto($request))->dataFromRequest();
            $course = $this->courseNoteService->update($course, $note, $data);
            DB::commit();

            return returnMessage(true, 'Course Notes Updated Successfully.', $course);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(CourseNoteRequest $request, Course $course, CourseNote $note): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->courseNoteService->delete($note);
            DB::commit();

            return returnMessage(true, 'Course Note Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
