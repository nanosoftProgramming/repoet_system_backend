<?php

namespace Modules\Enrollment\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Enrollment\App\Http\Requests\EnrollmentNoteUpdateStatusRequest;
use Modules\Enrollment\Service\EnrollmentNoteService;
use Modules\Enrollment\App\Models\EnrollmentNote;

class EnrollmentNoteAdminController extends Controller
{
    public function __construct(private EnrollmentNoteService $enrollmentNoteService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin');
    }
    public function index(Request $request): JsonResponse
    {
        $notes = $this->enrollmentNoteService->findAll($request->all(), ['enrollment.student']);
        return returnMessage(true, 'Notes Fetched Successfully.', $notes);
    }

    public function updateStatus(EnrollmentNoteUpdateStatusRequest $request, EnrollmentNote $note)
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
