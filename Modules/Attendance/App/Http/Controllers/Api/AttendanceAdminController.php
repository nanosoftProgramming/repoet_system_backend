<?php

namespace Modules\Attendance\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Attendance\App\Http\Requests\AttendanceAdminRequest;
use Modules\Course\App\Models\Course;
use Modules\Attendance\Service\AttendanceService;

class AttendanceAdminController extends Controller
{
    public function __construct(private AttendanceService $attendanceService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Trainer|Instructor');
    }
    public function index(Request $request)
    {
        $attendances = $this->attendanceService->findAll($request->all());
        return returnMessage(true, 'Attendance Fetched Successfully', $attendances);
    }
    public function todayAttendance(Request $request)
    {
        $todayAttendance = $this->attendanceService->findToday($request->all());
        return returnMessage(true, 'Attendance Fetched Successfully', $todayAttendance);
    }

    public function getStudentsByCourse(Request $request, Course $course)
    {
        $data = $request->all();
        $data['course_id'] = $course->id;
        $students = $this->attendanceService->getStudentsByCourse($data);
        return returnMessage(true, 'Students Fetched Successfully.', $students);
    }


    public function store(AttendanceAdminRequest $request)
    {
        $this->attendanceService->save($request->validated());
        return returnMessage(true, 'Attendance Created Successfully.');
    }
}
