<?php

namespace Modules\Course\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Imports\CoursesImport;
use Modules\Course\DTO\CourseDto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Course\App\Models\Course;
use Modules\Course\Service\CourseService;
use Modules\Course\App\Http\Requests\CourseRequest;
use Modules\Course\App\Http\Requests\CourseDeleteRequest;
use Modules\Course\App\Http\Requests\CourseImportRequest;

class CourseAdminController extends Controller
{
    public function __construct(private CourseService $courseService)
    {
        $this->middleware('auth:user');
        $this->middleware('role:Super Admin|Trainer|Instructor')->except(['accept', 'toggleActivate', 'import']);
        $this->middleware('role:Super Admin')->only(['accept', 'toggleActivate']);
    }
    public function index(Request $request)
    {
        $courses = $this->courseService->findAll($request->all(), ['trainer']);
        return returnMessage(true, 'Courses Fetched Successfully.', $courses);
    }


    public function store(CourseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new CourseDto($request))->dataFromRequest();
            $course = $this->courseService->save($data);
            DB::commit();
            return returnMessage(true, 'Course Created Successfully.', $course);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CourseRequest $request, Course $course)
    {
        try {
            DB::beginTransaction();
            $data = (new CourseDto($request))->dataFromRequest();
            $course = $this->courseService->update($course, $data);
            DB::commit();
            return returnMessage(true, 'Course Updated Successfully.', $course);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(CourseDeleteRequest $request, Course $course)
    {
        try {
            DB::beginTransaction();
            $this->courseService->delete($course);
            DB::commit();
            return returnMessage(true, 'Course Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }


    public function toggleActivate(Course $course)
    {
        try {
            DB::beginTransaction();
            $course = $this->courseService->toggleActivate($course);
            DB::commit();
            return returnMessage(true, 'Course Updated Successfully', $course);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function accept(Course $course)
    {
        try {
            DB::beginTransaction();
            $course->update(['is_accepted' => 1]);
            DB::commit();
            return returnMessage(true, 'Course Accepted Successfully', $course);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function import(CourseImportRequest $request)
    {
        try {
            $import = new CoursesImport();
            Excel::import($import, $request->file('file'));
            if (!empty($import->getFailures()))
                return returnValidationMessage(false, 'Validation Errors', $import->getFailures());
            return returnMessage(true, 'Courses Imported Successfully', null);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
