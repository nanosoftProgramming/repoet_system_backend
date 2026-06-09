<?php

namespace Modules\Attendance\Service;

use Illuminate\Support\Facades\DB;
use Modules\Attendance\App\Models\Attendance;
use Modules\Enrollment\App\Models\Enrollment;

class AttendanceService
{
    function findAll($data = [])
    {
        $query = Attendance::query()->available()->filter($data)->latest();
        return getCaseCollection($query, $data);
    }

    function findToday($data = [])
    {
        $query = Attendance::query()->available()->filter($data)->whereDate('created_at', today())->latest();
        return getCaseCollection($query, $data);
    }

    public function getStudentsByCourse($data)
    {
        return Enrollment::query()
            ->available()
            ->filter($data)
            ->where('is_completed', 0)
            ->where('is_paid', 1)
            ->latest()
            ->get()
            ->unique('student_id');
    }

    public function save($data): void
    {
        $enrollmentIds = array_column($data['attendances'], 'enrollment_id');
        $enrollments = Enrollment::with('course')->whereIn('id', $enrollmentIds)->get()->keyBy('id');

        DB::transaction(function () use ($data, $enrollments) {
            foreach ($data['attendances'] as $attendance) {
                $enrollment = $enrollments[$attendance['enrollment_id']];
                $enrollmentId = $attendance['enrollment_id'];
                $sessionNo = $data['session_no'];

                $existing = Attendance::where('enrollment_id', $enrollmentId)
                    ->where('session_no', $sessionNo)
                    ->first();

                $toSaveData = [
                    'enrollment_id' => $enrollmentId,
                    'student_id' => $enrollment->student_id,
                    'course_id' => $enrollment->course_id,
                    'is_present' => $attendance['is_present'],
                    'session_no' => $sessionNo,
                ];

                $existing ? $existing->update($toSaveData) : Attendance::create($toSaveData);

                $attendedSessionsCount = Attendance::where('enrollment_id', $enrollmentId)
                    ->distinct('session_no')
                    ->count('session_no');

                if ($attendedSessionsCount == $enrollment->course->sessions_no) {
                    $enrollment->update(['is_completed' => 1]);
                }
            }
        });
    }
}
