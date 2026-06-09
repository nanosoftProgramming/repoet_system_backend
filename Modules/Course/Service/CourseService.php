<?php

namespace Modules\Course\Service;

use Modules\Course\App\Models\Course;
use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;

class CourseService
{
    use UploadHelper;
    function findAll($data = [], $relations = []): mixed
    {
        $query = Course::query()
            ->with($relations)
            ->withCount(['enrollments'])
            ->available()
            ->filter($data)
            ->latest();
        return getCaseCollection($query, $data);
    }

    function findById($id)
    {
        return Course::findOrFail($id);
    }

    function findBy($key, $value)
    {
        return Course::where($key, $value)->get();
    }
    function active($data = [], $relations = []): mixed
    {
        $query = Course::query()
            ->with($relations)
            ->withCount(['enrollments'])
            ->available()
            ->active()
            ->filter($data)
            ->latest();
        return getCaseCollection($query, $data);
    }
    function save($data)
    {
        if (request()->hasFile('image'))
            $data['image'] = $this->upload(request()->file('image'), 'course');
        if (request()->hasFile('survey_file'))
            $data['survey_file'] = $this->uploadFile(request()->file('survey_file'), 'course/survey');

        if (request()->hasFile('details_file'))
            $data['details_file'] = $this->uploadFile(request()->file('details_file'), 'course/details');

        $course = Course::create($data);
        return $course;
    }

    function update($course, $data)
    {
        if (request()->hasFile('image')) {
            if ($course->image)
                File::delete(public_path('uploads/course/' . $this->getImageName('course', $course->image)));

            $data['image'] = $this->upload(request()->file('image'), 'course');
        }

        if (request()->hasFile('survey_file')) {
            if ($course->survey_file)
                File::delete(public_path('uploads/course/survey/' . $this->getImageName('course/survey', $course->survey_file)));

            $data['survey_file'] = $this->uploadFile(request()->file('survey_file'), 'course/survey');
        }

        if (request()->hasFile('details_file')) {
            if ($course->details_file)
                File::delete(public_path('uploads/course/details/' . $this->getImageName('course/details', $course->details_file)));

            $data['details_file'] = $this->uploadFile(request()->file('details_file'), 'course/details');
        }
        $course->update($data);
        return $course->fresh();
    }

    function delete($course)
    {
        if ($course->image)
            File::delete(public_path('uploads/course/' . $this->getImageName('course', $course->image)));
        if ($course->survey_file)
            File::delete(public_path('uploads/course/survey/' . $this->getImageName('course/survey', $course->survey_file)));
        if ($course->details_file)
            File::delete(public_path('uploads/course/details/' . $this->getImageName('course/details', $course->details_file)));

        $course->delete();
    }

    function activate($course)
    {
        $course->update(['is_active' => !$course->is_active]);
    }

    function toggleActivate($course)
    {
        $course->update([
            'is_active' => !$course->is_active
        ]);
        return $course->fresh();
    }
}
