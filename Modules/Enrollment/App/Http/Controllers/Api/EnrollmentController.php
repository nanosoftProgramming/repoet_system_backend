<?php

namespace Modules\Enrollment\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Enrollment\Service\EnrollmentService;
use Modules\Enrollment\App\Http\Requests\EnrollmentRequest;

class EnrollmentController extends Controller
{
    public function __construct(private EnrollmentService $enrollmentService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Student');
    }
    public function index(Request $request): JsonResponse
    {
        $enrollments = $this->enrollmentService->findAll($request->all(), ['attendances']);
        return returnMessage(true, 'Enrollments Fetched Successfully.', $enrollments);
    }
    public function store(EnrollmentRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $enrollment = $this->enrollmentService->save($request->validated());
            DB::commit();
            return returnMessage(true, 'Enrollment Created Successfully.', $enrollment);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
