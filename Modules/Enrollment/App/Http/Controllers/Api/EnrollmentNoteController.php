<?php

namespace Modules\Enrollment\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Enrollment\App\Models\Enrollment;
use Modules\Enrollment\App\Models\EnrollmentNote;
use Modules\Enrollment\Service\EnrollmentNoteService;
use Modules\Enrollment\App\Http\Requests\EnrollmentNoteRequest;

class EnrollmentNoteController extends Controller
{
    public function __construct(private EnrollmentNoteService $enrollmentNoteService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Student');
    }

    public function index(Request $request): JsonResponse
    {
        $notes = $this->enrollmentNoteService->findAll($request->all(), ['enrollment']);
        return returnMessage(true, 'Notes Fetched Successfully.', $notes);
    }

    public function store(EnrollmentNoteRequest $request, Enrollment $enrollment): JsonResponse
    {
        try {
            DB::beginTransaction();
            $enrollment = $this->enrollmentNoteService->save($enrollment, $request->validated());
            DB::commit();
            return returnMessage(true, 'Student Notes Updated Successfully.', $enrollment);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(EnrollmentNoteRequest $request, Enrollment $enrollment, EnrollmentNote $note): JsonResponse
    {
        try {
            DB::beginTransaction();
            $enrollment = $this->enrollmentNoteService->update($enrollment, $note, $request->validated());
            DB::commit();
            return returnMessage(true, 'Student Notes Updated Successfully.', $enrollment);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
