<?php

namespace Modules\Course\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Course\App\Http\Requests\CourseNoteUpdateStatusRequest;
use Modules\Course\App\Models\CourseNote;
use Modules\Course\Service\CourseNoteService;

class CourseNoteAdminController extends Controller
{
    public function __construct(private CourseNoteService $courseNoteService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin');
    }

    public function index(Request $request): JsonResponse
    {
        $notes = $this->courseNoteService->findAll($request->all(), ['course.trainer', 'trainer']);

        return returnMessage(true, 'Notes Fetched Successfully.', $notes);
    }

    public function updateStatus(CourseNoteUpdateStatusRequest $request, CourseNote $note)
    {
        try {
            DB::beginTransaction();
            $note->update($request->validated());
            DB::commit();

            return returnMessage(true, 'Note Updated Successfully.', $note->fresh());
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
