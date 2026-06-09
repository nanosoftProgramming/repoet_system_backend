<?php

namespace Modules\Course\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Course\Service\CourseService;


class CourseController extends Controller
{
    public function __construct(private CourseService $courseService)
    {
    }
    public function index(Request $request)
    {
        $courses = $this->courseService->active($request->all(), ['trainer']);
        return returnMessage(true, 'Courses Fetched Successfully.', $courses);
    }
}
