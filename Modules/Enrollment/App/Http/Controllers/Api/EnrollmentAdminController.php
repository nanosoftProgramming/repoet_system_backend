<?php

namespace Modules\Enrollment\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Enrollment\App\Models\Enrollment;
use Modules\Enrollment\Service\EnrollmentService;
use Modules\Enrollment\App\Http\Requests\EnrollmentAdminUpdateRequest;

class EnrollmentAdminController extends Controller
{
    public function __construct(private EnrollmentService $enrollmentService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Trainer');
    }
    public function index(Request $request): JsonResponse
    {
        $enrollments = $this->enrollmentService->findAll($request->all(), ['student']);
        return returnMessage(true, 'Enrollments Fetched Successfully.', $enrollments);
    }

    public function update(EnrollmentAdminUpdateRequest $request, Enrollment $enrollment): JsonResponse
    {
        try {
            DB::beginTransaction();
            $enrollment->update(['is_paid' => !$enrollment->is_paid]);
            DB::commit();
            return returnMessage(true, 'Enrollment ' . ($enrollment->is_paid ? 'Paid' : 'Unpaid') . ' Successfully.', $enrollment->fresh());
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
